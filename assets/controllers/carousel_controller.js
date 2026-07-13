import { Controller } from '@hotwired/stimulus';

/*
 * Carrousel générique à diapositives (translateX), avec autoplay optionnel.
 * Réutilisé pour le carrousel publicitaire (avec points de navigation) et
 * le méga-carrousel des articles urgents (sans points).
 *
 * Pourquoi un contrôleur Stimulus et pas un <script> classique :
 * Turbo Drive remplace le contenu de la page en AJAX lors de la navigation
 * (sans rechargement complet). Un <script> avec setInterval() attaché au
 * DOMContentLoaded ne se redéclenche pas après une navigation Turbo, et pire,
 * s'il se ré-exécute, il empile un second setInterval par-dessus le premier
 * (le carrousel accélère à chaque visite). Stimulus résout ça proprement :
 * connect() démarre le timer à chaque apparition réelle de l'élément dans le
 * DOM, disconnect() le détruit systématiquement quand l'élément en sort.
 */
export default class extends Controller {
    static targets = ['track', 'dot'];
    static values = {
        interval: { type: Number, default: 0 }, // 0 = pas de défilement automatique
    };

    connect() {
        this.index = 0;
        this.render();

        if (this.intervalValue > 0) {
            this.timer = setInterval(() => this.step(1), this.intervalValue);
        }
    }

    disconnect() {
        if (this.timer) {
            clearInterval(this.timer);
            this.timer = null;
        }
    }

    prev() {
        this.step(-1);
    }

    next() {
        this.step(1);
    }

    goTo(event) {
        this.index = parseInt(event.params.index, 10);
        this.render();
    }

    step(direction) {
        const count = this.trackTarget.children.length;
        if (count === 0) return;
        this.index = (this.index + direction + count) % count;
        this.render();
    }

    render() {
        this.trackTarget.style.transform = `translateX(-${this.index * 100}%)`;
        if (this.hasDotTarget) {
            this.dotTargets.forEach((dot, i) => {
                dot.setAttribute('data-active', i === this.index);
            });
        }
    }
}
