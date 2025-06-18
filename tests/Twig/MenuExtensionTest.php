<?php

namespace App\Tests\Twig;

use App\Twig\MenuExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuExtensionTest extends TestCase
{
    private function getExtensionWithRoute(string $routeName): MenuExtension
    {
        $request = new Request();
        $request->attributes->set('_route', $routeName);

        $stack = new RequestStack();
        $stack->push($request);

        return new MenuExtension($stack);
    }

    public function testHomeIsActive()
    {
        $ext = $this->getExtensionWithRoute('em_portfolio_backend');
        $this->assertTrue($ext->isActive(MenuExtension::HOME));
    }

    public function testProjectsIsActive()
    {
        $ext = $this->getExtensionWithRoute('em_portfolio_project_add');
        $this->assertTrue($ext->isActive(MenuExtension::PROJECTS));
    }

    public function testToolboxIsNotActive()
    {
        $ext = $this->getExtensionWithRoute('some_other_route');
        $this->assertFalse($ext->isActive(MenuExtension::TOOLBOX));
    }

    public function testInvalidMenuReturnsFalse()
    {
        $ext = $this->getExtensionWithRoute('em_portfolio_backend');
        $this->assertFalse($ext->isActive('invalid_key'));
    }
}
