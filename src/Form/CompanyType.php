<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public const COMPANY_FORM = 'company';
    public const COMPANY_SORT_FORM = 'company_sort';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formType = $options['form_type'];

        if (self::COMPANY_FORM === $formType) {
            $builder
                ->add('name', TextType::class, [
                    'label' => false,
                    'attr' => ['placeholder' => 'companies.form.name_placeholder'],
                ])
            ;
        }

        if (self::COMPANY_SORT_FORM === $formType) {
            $builder
                ->add('name', HiddenType::class, [])
                ->add('position', HiddenType::class, [])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
            'translation_domain' => 'admin',
            'form_type' => self::COMPANY_FORM,
        ]);

        $resolver->setAllowedValues('form_type', [self::COMPANY_FORM, self::COMPANY_SORT_FORM]);
    }
}
