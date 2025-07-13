import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    toggle(event) {
        const clickedCard = event.currentTarget;
        const isAlreadyFlipped = clickedCard.classList.contains('flipped');

        document.querySelectorAll('.recruiter-game-card.flipped').forEach(card => {
            card.classList.remove('flipped');
        });

        if (!isAlreadyFlipped) {
            clickedCard.classList.add('flipped');
        }
    }
}
