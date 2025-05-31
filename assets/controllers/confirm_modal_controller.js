import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['title', 'body', 'confirmButton'];

    open(event) {
        const trigger = event.currentTarget.closest('[data-confirm-url]');
        if (!trigger) {
            return;
        }

        const title = trigger.dataset.title || 'Confirmation';
        const body = trigger.dataset.body || 'You sure?';
        const confirmUrl = trigger.dataset.confirmUrl;

        this.titleTarget.textContent = title
        this.bodyTarget.textContent = body
        this.confirmButtonTarget.setAttribute("href", confirmUrl)
    }
}
