<?php

namespace App\Form;

use App\Entity\Enum\ToolCategory;
use App\Entity\Tool;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToolType extends AbstractType
{
    public const TOOL_FORM = 'tool';
    public const TOOL_SORT_FORM = 'tool_sort';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $formType = $options['form_type'];

        if (self::TOOL_FORM === $formType) {
            $builder
                ->add('name', TextType::class, [
                    'label' => false,
                    'attr' => ['placeholder' => 'tools.form.name_placeholder'],
                ])
                ->add('category', EnumType::class, [
                    'label' => false,
                    'class' => ToolCategory::class,
                    'placeholder' => 'tools.form.category_placeholder',
                ])
            ;
        }

        if (self::TOOL_SORT_FORM === $formType) {
            $builder
                ->add('name', HiddenType::class)
                ->add('position', HiddenType::class)
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tool::class,
            'translation_domain' => 'admin',
            'form_type' => self::TOOL_FORM,
        ]);

        $resolver->setAllowedValues('form_type', [self::TOOL_FORM, self::TOOL_SORT_FORM]);
    }
}
