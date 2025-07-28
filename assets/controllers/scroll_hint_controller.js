import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['answer', 'hint'];

    connect() {
        this.toggleHint();

        this.answerTarget.addEventListener('scroll', () => {
            const atBottom = this.answerTarget.scrollTop + this.answerTarget.clientHeight >= this.answerTarget.scrollHeight;
            this.hintTarget.style.opacity = atBottom ? '0' : '.5';
        });
    }

    toggleHint() {
        const isScrollable = this.answerTarget.scrollHeight > this.answerTarget.clientHeight || this.answerTarget.scrollHeight > 310;
        this.hintTarget.style.display = isScrollable ? 'absolute' : 'none';
    }
}
