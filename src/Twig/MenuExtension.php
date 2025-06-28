<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    public const HOME = 'home';
    public const PROJECTS = 'projects';
    public const TECHNOLOGIES = 'technologies';
    public const TAGS = 'tags';
    public const COMPANIES = 'companies';
    public const TOOLBOX = 'toolbox';
    public const RECRUITER_GAME = 'recruiter-game';
    public const TRANSLATIONS = 'translations';

    public function __construct(private RequestStack $requestStack) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_menu_active', [$this, 'isActive']),
        ];
    }

    public function isActive(string $menu): bool
    {
        $currentRoute = $this->requestStack->getCurrentRequest()?->attributes->get('_route');

        return match ($menu) {
            self::HOME => 'em_portfolio_backend' === $currentRoute,
            self::PROJECTS => in_array($currentRoute, ['em_portfolio_project_list', 'em_portfolio_project_add', 'em_portfolio_project_edit']),
            self::TECHNOLOGIES => 'em_portfolio_technology_list' === $currentRoute,
            self::TAGS => 'em_portfolio_tag_list' === $currentRoute,
            self::COMPANIES => 'em_portfolio_company_list' === $currentRoute,
            self::TOOLBOX => 'em_portfolio_backend_toolbox' === $currentRoute,
            self::RECRUITER_GAME => in_array($currentRoute, ['em_portfolio_recruiter_game_index', 'em_portfolio_recruiter_game_add', 'em_portfolio_recruiter_game_edit']),
            self::TRANSLATIONS => 'em_portfolio_translations' === $currentRoute,
            default => false,
        };
    }
}
