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
    public const TOOLBOX = 'toolbox';

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
            self::PROJECTS => in_array($currentRoute, ['em_portfolio_projects_list', 'em_portfolio_projects_add']),
            self::TECHNOLOGIES => 'em_portfolio_technologies_list' === $currentRoute,
            self::TOOLBOX => 'em_portfolio_backend_toolbox' === $currentRoute,
            default => false,
        };
    }
}
