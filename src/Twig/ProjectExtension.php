<?php

namespace App\Twig;

use App\Entity\Enum\Level;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ProjectExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_level_class', [$this, 'getLevelClassFromEnum']),
        ];
    }

    public function getLevelClassFromEnum(Level $level): string
    {
        return match ($level) {
            Level::SIDE_QUEST => 'level-medium',
            Level::BOSS_FIGHT => 'level-hard',
            Level::FINAL_BOSS => 'level-extra-hard',
            default => 'level-easy',
        };
    }
}
