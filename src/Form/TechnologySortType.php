<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TechnologySortType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('technologies', CollectionType::class, [
                'label' => false,
                'entry_type' => TechnologyType::class,
                'entry_options' => [
                    'label' => false,
                    'form_type' => TechnologyType::TECHNOLOGY_SORT_FORM,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
