import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['progressBar', 'progressPercent', 'copyIcon'];
    static values = {
        title: String,
        url: String,
        trackUrl: String,
    };

    connect() {
        // Tracking de vue asynchrone, hors du cache HTTP de la page (voir Jour 4).
        if (navigator.sendBeacon) {
            navigator.sendBeacon(this.trackUrlValue);
        } else {
            fetch(this.trackUrlValue, { method: 'POST', keepalive: true });
        }

        // La barre de progression de lecture écoute le scroll de la fenêtre.
        // Ce listener est sur `window`, pas sur this.element : il doit donc être
        // retiré explicitement dans disconnect(), sinon chaque navigation Turbo
        // vers cette page en empilerait un nouveau, sans jamais nettoyer les précédents.
        this.onScroll = this.onScroll.bind(this);
        window.addEventListener('scroll', this.onScroll);
    }

    disconnect() {
        window.removeEventListener('scroll', this.onScroll);
    }

    onScroll() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = height > 0 ? (winScroll / height) * 100 : 0;

        if (this.hasProgressBarTarget) {
            this.progressBarTarget.style.width = scrolled + '%';
        }
        if (this.hasProgressPercentTarget) {
            this.progressPercentTarget.textContent = Math.round(scrolled) + '%';
        }
    }

    shareWhatsapp() {
        const text = encodeURIComponent(this.titleValue);
        const url = encodeURIComponent(this.urlValue);
        const fullMessage = `${text}%20${url}`;

        const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
        const isAndroid = /Android/i.test(navigator.userAgent);
        const isMobile = isIOS || isAndroid;

        if (isMobile) {
            const whatsappUrl = isIOS
                ? `whatsapp://send?text=${fullMessage}`
                : `intent://send?text=${fullMessage}#Intent;package=com.whatsapp;scheme=whatsapp;end;`;
            window.location.href = whatsappUrl;
            setTimeout(() => {
                window.open(`https://wa.me/?text=${fullMessage}`, '_blank');
            }, 1000);
        } else {
            window.open(`https://web.whatsapp.com/send?text=${fullMessage}`, '_blank', 'width=800,height=600');
        }
    }

    shareFacebook() {
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(this.urlValue)}`, '_blank', 'width=600,height=400');
    }

    shareTwitter() {
        window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(this.titleValue)}&url=${encodeURIComponent(this.urlValue)}`, '_blank', 'width=600,height=400');
    }

    shareLinkedin() {
        window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(this.urlValue)}`, '_blank', 'width=600,height=400');
    }

    copyLink() {
        navigator.clipboard.writeText(this.urlValue).then(() => {
            if (!this.hasCopyIconTarget) return;
            const original = this.copyIconTarget.innerHTML;
            this.copyIconTarget.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
            setTimeout(() => {
                this.copyIconTarget.innerHTML = original;
            }, 2000);
        });
    }
}
