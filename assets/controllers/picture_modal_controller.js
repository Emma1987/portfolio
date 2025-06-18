import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['imgPreview'];

    open(event) {
        const imgSrc = event.currentTarget.dataset.imgSrc;
        if (!imgSrc) {
            return;
        }

        this.imgPreviewTarget.src = imgSrc;
    }
}
