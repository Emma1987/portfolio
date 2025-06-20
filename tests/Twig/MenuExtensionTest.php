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
        $this->assertFalse($ext->isActive(MenuExtension::PROJECTS));
        $this->assertFalse($ext->isActive(MenuExtension::TECHNOLOGIES));
        $this->assertFalse($ext->isActive(MenuExtension::TAGS));
        $this->assertFalse($ext->isActive(MenuExtension::COMPANIES));
        $this->assertFalse($ext->isActive(MenuExtension::TOOLBOX));
        $this->assertFalse($ext->isActive(MenuExtension::RECRUITER_GAME));
    }

    public function testProjectsIsActive()
    {
        $ext = $this->getExtensionWithRoute('em_portfolio_project_list');
        $this->assertFalse($ext->isActive(MenuExtension::HOME));
        $this->assertTrue($ext->isActive(MenuExtension::PROJECTS));
        $this->assertFalse($ext->isActive(MenuExtension::TECHNOLOGIES));
        $this->assertFalse($ext->isActive(MenuExtension::TAGS));
        $this->assertFalse($ext->isActive(MenuExtension::COMPANIES));
        $this->assertFalse($ext->isActive(MenuExtension::TOOLBOX));
        $this->assertFalse($ext->isActive(MenuExtension::RECRUITER_GAME));

        $ext = $this->getExtensionWithRoute('em_portfolio_project_add');
        $this->assertFalse($ext->isActive(MenuExtension::HOME));
        $this->assertTrue($ext->isActive(MenuExtension::PROJECTS));
        $this->assertFalse($ext->isActive(MenuExtension::TECHNOLOGIES));
        $this->assertFalse($ext->isActive(MenuExtension::TAGS));
        $this->assertFalse($ext->isActive(MenuExtension::COMPANIES));
        $this->assertFalse($ext->isActive(MenuExtension::TOOLBOX));
        $this->assertFalse($ext->isActive(MenuExtension::RECRUITER_GAME));

        $ext = $this->getExtensionWithRoute('em_portfolio_project_edit');
        $this->assertFalse($ext->isActive(MenuExtension::HOME));
        $this->assertTrue($ext->isActive(MenuExtension::PROJECTS));
        $this->assertFalse($ext->isActive(MenuExtension::TECHNOLOGIES));
        $this->assertFalse($ext->isActive(MenuExtension::TAGS));
        $this->assertFalse($ext->isActive(MenuExtension::COMPANIES));
        $this->assertFalse($ext->isActive(MenuExtension::TOOLBOX));
        $this->assertFalse($ext->isActive(MenuExtension::RECRUITER_GAME));
    }

    public function testTechnologiesIsActive()
    {
        $ext = $this->getExtensionWithRoute('em_portfolio_technology_list');
        $this->assertFalse($ext->isActive(MenuExtension::HOME));
        $this->assertFalse($ext->isActive(MenuExtension::PROJECTS));
        $this->assertTrue($ext->isActive(MenuExtension::TECHNOLOGIES));
        $this->assertFalse($ext->isActive(MenuExtension::TAGS));
        $this->assertFalse($ext->isActive(MenuExtension::COMPANIES));
        $this->assertFalse($ext->isActive(MenuExtension::TOOLBOX));
        $this->assertFalse($ext->isActive(MenuExtension::RECRUITER_GAME));
    }

    public function testTagsIsActive()
    {
        $ext = $this->getExtensionWithRoute('em_portfolio_tag_list');
        $this->assertFalse($ext->isActive(MenuExtension::HOME));
        $this->assertFalse($ext->isActive(MenuExtension::PROJECTS));
        $this->assertFalse($ext->isActive(MenuExtension::TECHNOLOGIES));
        $this->assertTrue($ext->isActive(MenuExtension::TAGS));
        $this->assertFalse($ext->isActive(MenuExtension::COMPANIES));
        $this->assertFalse($ext->isActive(MenuExtension::TOOLBOX));
        $this->assertFalse($ext->isActive(MenuExtension::RECRUITER_GAME));
    }

    public function testCompaniesIsActive()
    {
        $ext = $this->getExtensionWithRoute('em_portfolio_company_list');
        $this->assertFalse($ext->isActive(MenuExtension::HOME));
        $this->assertFalse($ext->isActive(MenuExtension::PROJECTS));
        $this->assertFalse($ext->isActive(MenuExtension::TECHNOLOGIES));
        $this->assertFalse($ext->isActive(MenuExtension::TAGS));
        $this->assertTrue($ext->isActive(MenuExtension::COMPANIES));
        $this->assertFalse($ext->isActive(MenuExtension::TOOLBOX));
        $this->assertFalse($ext->isActive(MenuExtension::RECRUITER_GAME));
    }

    public function testToolboxIsActive()
    {
        $ext = $this->getExtensionWithRoute('em_portfolio_backend_toolbox');
        $this->assertFalse($ext->isActive(MenuExtension::HOME));
        $this->assertFalse($ext->isActive(MenuExtension::PROJECTS));
        $this->assertFalse($ext->isActive(MenuExtension::TECHNOLOGIES));
        $this->assertFalse($ext->isActive(MenuExtension::TAGS));
        $this->assertFalse($ext->isActive(MenuExtension::COMPANIES));
        $this->assertTrue($ext->isActive(MenuExtension::TOOLBOX));
        $this->assertFalse($ext->isActive(MenuExtension::RECRUITER_GAME));
    }

    public function testRecruiterGameIsActive()
    {
        $ext = $this->getExtensionWithRoute('em_portfolio_recruiter_game_index');
        $this->assertFalse($ext->isActive(MenuExtension::HOME));
        $this->assertFalse($ext->isActive(MenuExtension::PROJECTS));
        $this->assertFalse($ext->isActive(MenuExtension::TECHNOLOGIES));
        $this->assertFalse($ext->isActive(MenuExtension::TAGS));
        $this->assertFalse($ext->isActive(MenuExtension::COMPANIES));
        $this->assertFalse($ext->isActive(MenuExtension::TOOLBOX));
        $this->assertTrue($ext->isActive(MenuExtension::RECRUITER_GAME));

        $ext = $this->getExtensionWithRoute('em_portfolio_recruiter_game_add');
        $this->assertFalse($ext->isActive(MenuExtension::HOME));
        $this->assertFalse($ext->isActive(MenuExtension::PROJECTS));
        $this->assertFalse($ext->isActive(MenuExtension::TECHNOLOGIES));
        $this->assertFalse($ext->isActive(MenuExtension::TAGS));
        $this->assertFalse($ext->isActive(MenuExtension::COMPANIES));
        $this->assertFalse($ext->isActive(MenuExtension::TOOLBOX));
        $this->assertTrue($ext->isActive(MenuExtension::RECRUITER_GAME));

        $ext = $this->getExtensionWithRoute('em_portfolio_recruiter_game_edit');
        $this->assertFalse($ext->isActive(MenuExtension::HOME));
        $this->assertFalse($ext->isActive(MenuExtension::PROJECTS));
        $this->assertFalse($ext->isActive(MenuExtension::TECHNOLOGIES));
        $this->assertFalse($ext->isActive(MenuExtension::TAGS));
        $this->assertFalse($ext->isActive(MenuExtension::COMPANIES));
        $this->assertFalse($ext->isActive(MenuExtension::TOOLBOX));
        $this->assertTrue($ext->isActive(MenuExtension::RECRUITER_GAME));
    }

    public function testInvalidMenuReturnsFalse()
    {
        $ext = $this->getExtensionWithRoute('em_portfolio_backend');
        $this->assertFalse($ext->isActive('invalid_key'));
    }
}
