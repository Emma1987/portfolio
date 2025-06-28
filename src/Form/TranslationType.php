<?php

namespace App\Form;

use App\Entity\DTO\TranslationDTO;
use App\Form\EventSubscriber\TranslationSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class TranslationType extends AbstractType
{
    public function __construct(private TranslationSubscriber $translationSubscriber) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('entity', TextType::class, [
                'label' => new TranslatableMessage('translations.form.entity', [], 'admin'),
                'disabled' => true,
            ])
            ->add('field', TextType::class, [
                'label' => new TranslatableMessage('translations.form.field', [], 'admin'),
                'disabled' => true,
            ])
        ;

        $builder->addEventSubscriber($this->translationSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TranslationDTO::class,
            'translation_domain' => 'admin',
        ]);
    }
}
