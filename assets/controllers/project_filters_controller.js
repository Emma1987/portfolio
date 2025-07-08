import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['technologiesInput', 'tagsInput', 'companiesInput', 'projectCard'];

    filter() {
        const selectedTechnologies = Array.from(this.technologiesInputTarget.selectedOptions).map(option => option.value);
        const selectedTags = Array.from(this.tagsInputTarget.selectedOptions).map(option => option.value);
        const selectedCompanies = Array.from(this.companiesInputTarget.selectedOptions).map(option => option.value);

        this.projectCardTargets.forEach((project) => {
            const projectTechnologies = project.dataset.technologies?.split(',') || [];
            const projectTags = project.dataset.tags?.split(',') || [];
            const projectCompanies = project.dataset.companies?.split(',') || [];

            const matchesTechnologies =
                selectedTechnologies.length === 0 ||
                selectedTechnologies.some(technology => projectTechnologies.includes(technology));

            const matchesTags =
                selectedTags.length === 0 ||
                selectedTags.some(tag => projectTags.includes(tag));

            const matchesCompanies =
                selectedCompanies.length === 0 ||
                selectedCompanies.some(company => projectCompanies.includes(company));

            project.classList.toggle('d-none', !(matchesTechnologies && matchesTags && matchesCompanies));
        })
    }
}
