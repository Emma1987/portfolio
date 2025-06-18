<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Tag;
use App\Tests\AdminWebTestCase;

class TagControllerTest extends AdminWebTestCase
{
    public function testRenderTagsIsSuccessful()
    {
        $this->client->request('GET', '/backstage/tags');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('.tags-card');
        $this->assertSelectorExists('form[name=tag]');
        $this->assertSelectorExists('form[name=tag_sort]');
    }

    public function testAddTag(): void
    {
        $savedTags = $this->entityManager->getRepository(Tag::class)->count();
        $crawler = $this->client->request('GET', '/backstage/tags');

        $form = $crawler->filter('form[name=tag]')->form();
        $form['tag[name]'] = 'CryptHooks';

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/tags');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'New tag "CryptHooks" created!');

        $tag = $this->entityManager->getRepository(Tag::class)->findOneBy(['name' => 'CryptHooks']);
        $this->assertNotNull($tag);
        $this->assertSame($savedTags + 1, $tag->getPosition());
    }

    public function testAddAlreadyExistingTag(): void
    {
        $tags = $this->entityManager->getRepository(Tag::class)->findAll();
        $existingTag = array_shift($tags);

        $crawler = $this->client->request('GET', '/backstage/tags');

        $form = $crawler->filter('form[name=tag]')->form();
        $form['tag[name]'] = $existingTag->getName();

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/tags');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-danger');
        $this->assertSelectorTextContains('.alert-danger', 'Don\'t remember you already created this one?');
    }

    public function testSortTags(): void
    {
        $tags = $this->entityManager->getRepository(Tag::class)->findAll();

        $crawler = $this->client->request('GET', '/backstage/tags');

        $form = $crawler->filter('form[name=tag_sort]')->form();
        $formValues = [];
        foreach ($tags as $tag) {
            $key = $tag->getPosition() - 1;
            $this->assertSelectorExists("input[name='tag_sort[tags][{$key}][name]']");
            $this->assertSelectorExists("input[name='tag_sort[tags][{$key}][position]']");

            $this->assertSame($tag->getName(), $form['tag_sort[tags][' . $key . '][name]']->getValue());
            $this->assertEquals($tag->getPosition(), $form['tag_sort[tags][' . $key . '][position]']->getValue());

            $formValues[$key] = $tag;
        }

        $newCount = 114;
        foreach ($formValues as $key => $formValue) {
            $form['tag_sort[tags][' . $key . '][position]'] = $newCount;
            ++$newCount;
        }

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/tags');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Logged. Saved. Handled. You rock.');

        $newPosition = 114;
        foreach ($tags as $tag) {
            $updatedTag = $this->entityManager->getRepository(Tag::class)->find($tag->getId());
            $this->assertEquals($updatedTag->getPosition(), $newPosition);
            ++$newPosition;
        }
    }

    public function testDeleteTag(): void
    {
        $tags = $this->entityManager->getRepository(Tag::class)->findAll();
        $existingTag = array_shift($tags);
        $id = $existingTag->getId();

        $this->client->request('GET', "/backstage/tag/delete/{$id}");

        $this->assertResponseRedirects('/backstage/tags');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Twig smiled. Your action worked.');

        $deletedTag = $this->entityManager->getRepository(Tag::class)->find($id);
        $this->assertNull($deletedTag);
    }
}
