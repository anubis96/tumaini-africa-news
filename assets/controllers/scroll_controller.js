import { Controller } from '@hotwired/stimulus';

// Défilement horizontal (scrollBy) pour la rangée "Tendances de la semaine".
export default class extends Controller {
    static targets = ['container'];
    static values = {
        step: { type: Number, default: 300 },
    };

    scrollPrev() {
        this.containerTarget.scrollBy({ left: -this.stepValue, behavior: 'smooth' });
    }

    scrollNext() {
        this.containerTarget.scrollBy({ left: this.stepValue, behavior: 'smooth' });
    }
}
