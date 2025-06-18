<?php

namespace App\Form;

use App\Entity\Technology;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TechnologyType extends AbstractType
{
    public const TECHNOLOGY_FORM = 'technology';
    public const TECHNOLOGY_SORT_FORM = 'technology_sort';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formType = $options['form_type'];

        if (self::TECHNOLOGY_FORM === $formType) {
            $builder
                ->add('name', TextType::class, [
                    'label' => false,
                    'attr' => ['placeholder' => 'technologies.form.name_placeholder'],
                ])
            ;
        }

        if (self::TECHNOLOGY_SORT_FORM === $formType) {
            $builder
                ->add('name', HiddenType::class, [])
                ->add('position', HiddenType::class, [])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Technology::class,
            'translation_domain' => 'admin',
            'form_type' => self::TECHNOLOGY_FORM,
        ]);

        $resolver->setAllowedValues('form_type', [self::TECHNOLOGY_FORM, self::TECHNOLOGY_SORT_FORM]);
    }
}
