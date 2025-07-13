<?php

namespace App\Tests\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublicControllerTest extends WebTestCase
{
    public function testRenderHomeIsSuccessful()
    {
        $client = static::createClient();

        $client->request('GET', '/fr/');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('#introduction');
        $this->assertSelectorExists('.profile-pic-container');
        $this->assertSelectorExists('.introduction-text');
    }

    public function testRenderProjectsIsSuccessful()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $projects = $entityManager->getRepository(Project::class)->count();

        $crawler = $client->request('GET', '/fr/projects');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('#projects-grid');
        $this->assertSelectorExists('#project-cards');
        $this->assertSame($projects, $crawler->filter('.project-card')->count());
    }

    public function testRenderAboutIsSuccessful()
    {
        $client = static::createClient();

        $client->request('GET', '/fr/about');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('#about');
    }

    public function testRenderRecruiterGameIsSuccessful()
    {
        $client = static::createClient();

        $client->request('GET', '/fr/recruiter-game');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('#recruiter-game');
    }

    public function testRenderContactIsSuccessful()
    {
        $client = static::createClient();

        $client->request('GET', '/fr/contact');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('#contact');
    }
}
