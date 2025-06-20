<?php

namespace App\Form;

use App\Entity\Enum\InterviewPromptCategory;
use App\Entity\InterviewPrompt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class InterviewPromptType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $translator = $this->translator;

        $builder
            ->add('question', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'recruiter_game.form.question_placeholder'],
            ])
            ->add('answer', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'recruiter_game.form.answer_placeholder'],
            ])
            ->add('category', EnumType::class, [
                'label' => false,
                'class' => InterviewPromptCategory::class,
                'placeholder' => 'recruiter_game.form.category_placeholder',
                'choice_label' => function ($choice) use ($translator) {
                    return $translator->trans('enum.interview_prompt_category.' . $choice->value, [], 'admin') . ' - ' . $translator->trans('enum.interview_prompt_category.' . $choice->value . '_help', [], 'admin');
                },
                'choice_translation_domain' => 'messages',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InterviewPrompt::class,
            'translation_domain' => 'admin',
        ]);
    }
}
