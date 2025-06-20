<?php

namespace App\Entity\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ToolCategory: string implements TranslatableInterface
{
    case LANGUAGES = 'LANGUAGES';
    case FRAMEWORKS = 'FRAMEWORKS';
    case TOOLS = 'TOOLS';
    case PRACTICES = 'PRACTICES';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('enum.tool_category.' . strtolower($this->name), locale: $locale);
    }
}
