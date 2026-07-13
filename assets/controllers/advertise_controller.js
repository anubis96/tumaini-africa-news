import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['title', 'body'];

    open(event) {
        const { title, description, company, phone, email, image } = event.params;

        this.titleTarget.textContent = title || '';

        let html = '<div class="space-y-3">';
        if (description) {
            html += `<p>${this.escapeHtml(description)}</p>`;
        }
        html += '<div class="grid grid-cols-2 gap-3 text-sm">';
        html += `<div><span class="font-semibold">Entreprise:</span> ${this.escapeHtml(company) || '—'}</div>`;
        if (phone) {
            html += `<div><span class="font-semibold">Tél:</span> ${this.escapeHtml(phone)}</div>`;
        }
        if (email) {
            html += `<div><span class="font-semibold">Email:</span> ${this.escapeHtml(email)}</div>`;
        }
        html += '</div>';
        if (image) {
            // L'URL d'image vient de vich_uploader_asset() côté serveur, pas d'une saisie libre : pas besoin d'échappement ici.
            html += `<img src="${image}" class="rounded-lg mt-2" loading="lazy" alt="${this.escapeHtml(title)}">`;
        }
        html += '</div>';

        this.bodyTarget.innerHTML = html;

        this.element.classList.remove('hidden');
        this.element.classList.add('flex');
    }

    close() {
        this.element.classList.add('hidden');
        this.element.classList.remove('flex');
    }

    // Ferme la modale si on clique sur le fond sombre (en dehors de la boîte de dialogue)
    closeOnBackdrop(event) {
        if (event.target === this.element) {
            this.close();
        }
    }

    escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
}
