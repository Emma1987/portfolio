<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Tag;
use App\Entity\Technology;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProjectFilterType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('technologies', EntityType::class, [
                'label' => 'projects.technologies',
                'class' => Technology::class,
                'required' => false,
                'mapped' => false,
                'choice_label' => 'name',
                'autocomplete' => true,
                'multiple' => true,
                'tom_select_options' => [
                    'placeholder' => $this->translator->trans('projects.filters.technologies_placeholder'),
                    'hidePlaceholder' => true,
                ],
            ])
            ->add('tags', EntityType::class, [
                'label' => 'projects.tags',
                'class' => Tag::class,
                'required' => false,
                'mapped' => false,
                'choice_label' => 'name',
                'autocomplete' => true,
                'multiple' => true,
                'tom_select_options' => [
                    'placeholder' => $this->translator->trans('projects.filters.tags_placeholder'),
                    'hidePlaceholder' => true,
                ],
            ])
            ->add('companies', EntityType::class, [
                'label' => 'projects.companies',
                'class' => Company::class,
                'required' => false,
                'choice_label' => 'name',
                'autocomplete' => true,
                'multiple' => true,
                'tom_select_options' => [
                    'placeholder' => $this->translator->trans('projects.filters.companies_placeholder'),
                    'hidePlaceholder' => true,
                ],
            ])
        ;
    }
}
