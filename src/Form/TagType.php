<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    public const TAG_FORM = 'tag';
    public const TAG_SORT_FORM = 'tag_sort';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formType = $options['form_type'];

        if (self::TAG_FORM === $formType) {
            $builder
                ->add('name', TextType::class, [
                    'label' => false,
                    'attr' => ['placeholder' => 'tags.form.name_placeholder'],
                ])
            ;
        }

        if (self::TAG_SORT_FORM === $formType) {
            $builder
                ->add('name', HiddenType::class, [])
                ->add('position', HiddenType::class, [])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
            'translation_domain' => 'admin',
            'form_type' => self::TAG_FORM,
        ]);

        $resolver->setAllowedValues('form_type', [self::TAG_FORM, self::TAG_SORT_FORM]);
    }
}
