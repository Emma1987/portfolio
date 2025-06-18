<?php

namespace App\Entity\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum Level: string implements TranslatableInterface
{
    case NoobQuest = 'NOOB_QUEST';
    case SideQuest = 'SIDE_QUEST';
    case BossFight = 'BOSS_FIGHT';
    case FinalBoss = 'FINAL_BOSS';

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
