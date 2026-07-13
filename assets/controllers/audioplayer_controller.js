import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        'audio', 'playButton', 'playIcon', 'pauseIcon',
        'progressFill', 'progressContainer', 'currentTime', 'duration', 'speedBtn',
    ];

    static SPEEDS = [0.75, 1, 1.25, 1.5, 2];

    connect() {
        this.currentSpeed = 1;

        this.onLoadedMetadata = () => {
            this.durationTarget.textContent = this.formatTime(this.audioTarget.duration);
        };
        this.onTimeUpdate = () => {
            const progress = (this.audioTarget.currentTime / this.audioTarget.duration) * 100;
            this.progressFillTarget.style.width = progress + '%';
            this.currentTimeTarget.textContent = this.formatTime(this.audioTarget.currentTime);
        };
        this.onEnded = () => {
            this.playIconTarget.classList.remove('hidden');
            this.pauseIconTarget.classList.add('hidden');
            this.progressFillTarget.style.width = '0%';
            this.currentTimeTarget.textContent = '0:00';
        };

        this.audioTarget.addEventListener('loadedmetadata', this.onLoadedMetadata);
        this.audioTarget.addEventListener('timeupdate', this.onTimeUpdate);
        this.audioTarget.addEventListener('ended', this.onEnded);
    }

    disconnect() {
        this.audioTarget.removeEventListener('loadedmetadata', this.onLoadedMetadata);
        this.audioTarget.removeEventListener('timeupdate', this.onTimeUpdate);
        this.audioTarget.removeEventListener('ended', this.onEnded);
    }

    togglePlay() {
        if (this.audioTarget.paused) {
            this.audioTarget.play();
            this.playIconTarget.classList.add('hidden');
            this.pauseIconTarget.classList.remove('hidden');
        } else {
            this.audioTarget.pause();
            this.playIconTarget.classList.remove('hidden');
            this.pauseIconTarget.classList.add('hidden');
        }
    }

    seek(event) {
        const rect = this.progressContainerTarget.getBoundingClientRect();
        const pos = (event.clientX - rect.left) / rect.width;
        this.audioTarget.currentTime = pos * this.audioTarget.duration;
    }

    cycleSpeed() {
        const speeds = this.constructor.SPEEDS;
        const currentIndex = speeds.indexOf(this.currentSpeed);
        this.currentSpeed = speeds[(currentIndex + 1) % speeds.length];
        this.audioTarget.playbackRate = this.currentSpeed;
        this.speedBtnTarget.textContent = this.currentSpeed + 'x';
    }

    formatTime(seconds) {
        if (isNaN(seconds)) return '0:00';
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
    }
}
