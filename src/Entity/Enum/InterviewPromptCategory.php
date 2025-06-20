<?php

namespace App\Entity\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum InterviewPromptCategory: string implements TranslatableInterface
{
    case MOTIVATION = 'motivation';
    case PERSONALITY = 'personality';
    case EXPERIENCE = 'experience';
    case EDUCATION = 'education';
    case TECHNICAL = 'technical';
    case PROBLEM_SOLVING = 'problem_solving';
    case TEAMWORK = 'teamwork';
    case ADAPTABILITY = 'adaptability';
    case AMBITION = 'ambition';
    case FAILURE = 'failure';
    case ORGANIZATION = 'organization';
    case CULTURAL_FIT = 'cultural_fit';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('enum.interview_prompt_category.' . strtolower($this->name), locale: $locale);
    }
}
