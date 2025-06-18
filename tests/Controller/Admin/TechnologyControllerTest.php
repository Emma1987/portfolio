<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Technology;
use App\Tests\AdminWebTestCase;

class TechnologyControllerTest extends AdminWebTestCase
{
    public function testRenderTechnologiesIsSuccessful()
    {
        $this->client->request('GET', '/backstage/technologies');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('.technologies-card');
        $this->assertSelectorExists('form[name=technology]');
        $this->assertSelectorExists('form[name=technology_sort]');
    }

    public function testAddTechnology(): void
    {
        $savedTechnologies = $this->entityManager->getRepository(Technology::class)->count();
        $crawler = $this->client->request('GET', '/backstage/technologies');

        $form = $crawler->filter('form[name=technology]')->form();
        $form['technology[name]'] = 'ChainsawJS';

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/technologies');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'New technology "ChainsawJS" created!');

        $technology = $this->entityManager->getRepository(Technology::class)->findOneBy(['name' => 'ChainsawJS']);
        $this->assertNotNull($technology);
        $this->assertSame($savedTechnologies + 1, $technology->getPosition());
    }

    public function testAddAlreadyExistingTechnology(): void
    {
        $technologies = $this->entityManager->getRepository(Technology::class)->findAll();
        $existingTechnology = array_shift($technologies);

        $crawler = $this->client->request('GET', '/backstage/technologies');

        $form = $crawler->filter('form[name=technology]')->form();
        $form['technology[name]'] = $existingTechnology->getName();

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/technologies');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-danger');
        $this->assertSelectorTextContains('.alert-danger', 'Don\'t remember you already created this one?');
    }

    public function testSortTechnologies(): void
    {
        $technologies = $this->entityManager->getRepository(Technology::class)->findAll();

        $crawler = $this->client->request('GET', '/backstage/technologies');

        $form = $crawler->filter('form[name=technology_sort]')->form();
        $formValues = [];
        foreach ($technologies as $technology) {
            $key = $technology->getPosition() - 1;
            $this->assertSelectorExists("input[name='technology_sort[technologies][{$key}][name]']");
            $this->assertSelectorExists("input[name='technology_sort[technologies][{$key}][position]']");

            $this->assertSame($technology->getName(), $form['technology_sort[technologies][' . $key . '][name]']->getValue());
            $this->assertEquals($technology->getPosition(), $form['technology_sort[technologies][' . $key . '][position]']->getValue());

            $formValues[$key] = $technology;
        }

        $newCount = 114;
        foreach ($formValues as $key => $formValue) {
            $form['technology_sort[technologies][' . $key . '][position]'] = $newCount;
            ++$newCount;
        }

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/technologies');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Boom. It worked. You rock.');

        $newPosition = 114;
        foreach ($technologies as $technology) {
            $updatedTechnology = $this->entityManager->getRepository(Technology::class)->find($technology->getId());
            $this->assertEquals($updatedTechnology->getPosition(), $newPosition);
            ++$newPosition;
        }
    }

    public function testDeleteTechnology(): void
    {
        $technologies = $this->entityManager->getRepository(Technology::class)->findAll();
        $existingTechnology = array_shift($technologies);
        $id = $existingTechnology->getId();

        $this->client->request('GET', "/backstage/technology/delete/{$id}");

        $this->assertResponseRedirects('/backstage/technologies');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Success. You’ve earned yourself a coffee. Or a nap.');

        $deletedTechnology = $this->entityManager->getRepository(Technology::class)->find($id);
        $this->assertNull($deletedTechnology);
    }
}
