<?php

namespace App\Entity\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum Level: string implements TranslatableInterface
{
    case NOOB_QUEST = 'NOOB_QUEST';
    case SIDE_QUEST = 'SIDE_QUEST';
    case BOSS_FIGHT = 'BOSS_FIGHT';
    case FINAL_BOSS = 'FINAL_BOSS';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('enum.level.' . strtolower($this->name), locale: $locale);
    }
}
