<?php

namespace App\Entity\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ToolCategory: string implements TranslatableInterface
{
    case Languages = 'LANGUAGES';
    case Frameworks = 'FRAMEWORKS';
    case Tools = 'TOOLS';
    case Practices = 'PRACTICES';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('enum.tool_category.' . strtolower($this->name), locale: $locale);
    }
}
