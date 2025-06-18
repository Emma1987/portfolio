import { Controller } from '@hotwired/stimulus';
import Sortable from 'sortablejs';

export default class extends Controller {
    static targets = ['list', 'item'];

    connect() {
        this.sortable = Sortable.create(this.listTarget, {
            animation: 350,
            onEnd: () => this.updatePositions(),
        });

        this.updatePositions();
    }

    updatePositions() {
        this.itemTargets.forEach((element, index) => {
            const positionInput = element.querySelector('input[name$="[position]"]');

            if (positionInput) {
                positionInput.value = index + 1;
            }
        })
    }
}
