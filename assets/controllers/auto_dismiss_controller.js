import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        delay: { type: Number, default: 4000 }
    }

    connect() {
        this.autoDismiss();
    }

    autoDismiss() {
        setTimeout(() => {
            this.element.classList.remove('show');
            setTimeout(() => this.element.remove(), 500);
        }, this.delayValue);
    }
}
