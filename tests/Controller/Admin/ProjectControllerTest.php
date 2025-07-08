<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Company;
use App\Entity\Enum\Level;
use App\Entity\Picture;
use App\Entity\Project;
use App\Entity\ProjectTag;
use App\Entity\ProjectTechnology;
use App\Entity\Tag;
use App\Entity\Technology;
use App\Storage\PictureHandler;
use App\Tests\AdminWebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjectControllerTest extends AdminWebTestCase
{
    public function testRenderProjectsIsSuccessful()
    {
        $this->client->request('GET', '/backstage/projects');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('.project-cards');
    }

    public function testCreateProject(): void
    {
        $bloodyDBTechnology = $this->entityManager->getRepository(Technology::class)->findOneBy(['name' => 'BloodyDB']);
        $knifeJSTechnology = $this->entityManager->getRepository(Technology::class)->findOneBy(['name' => 'Knife.js']);
        $deadSignTag = $this->entityManager->getRepository(Tag::class)->findOneBy(['name' => 'DeadSign']);
        $corpsCompany = $this->entityManager->getRepository(Company::class)->findOneBy(['name' => '404 Corps']);
        $fakePictureHandler = static::getContainer()->get(PictureHandler::class);

        $crawler = $this->client->request('GET', '/backstage/project/add');
        $this->assertResponseIsSuccessful();

        // Assert that the element with ID "existing-pictures" is not present
        $this->assertSelectorNotExists('#existing-pictures');

        // Assert that no calls were made yet to the FakePictureHandler methods
        $this->assertEquals(0, $fakePictureHandler->uploadProjectPictureCalls);
        $this->assertEquals(0, $fakePictureHandler->deleteProjectPictureCalls);

        $form = $crawler->filter('form[name=project]')->form();
        $form['project[title]'] = 'Vampire Social Network';
        $form['project[summary]'] = 'Where night owls and immortals connect.';
        $form['project[description]'] = 'No sunlight. No garlic. Just fangs, friends, and eternal drama. 🦇💬';
        $form['project[technologies]'] = [$bloodyDBTechnology->getId(), $knifeJSTechnology->getId()];
        $form['project[tags]'] = [$deadSignTag->getId()];
        $form['project[companies]'] = [$corpsCompany->getId()];
        $form['project[level]'] = '0';

        $this->client->request(
            $form->getMethod(),
            $form->getUri(),
            $form->getPhpValues(),
            [
                'project' => [
                    'pictures' => [
                        0 => new UploadedFile(__DIR__ . '/../../fixtures/image.jpeg', 'image.jpeg'),
                    ],
                ],
            ],
        );

        $this->assertResponseRedirects('/backstage/projects');

        // Assert that the FakePictureHandler's uploadProjectPicture method was called once
        $this->assertEquals(1, $fakePictureHandler->uploadProjectPictureCalls);
        $this->assertEquals(0, $fakePictureHandler->deleteProjectPictureCalls);

        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Project created! Look at you go, building things like a pro. 🚀');

        /** @var Project $project */
        $project = $this->entityManager->getRepository(Project::class)->findOneBy(['title' => 'Vampire Social Network']);
        $this->assertNotNull($project);
        $this->assertSame('Vampire Social Network', $project->getTitle());
        $this->assertSame('Where night owls and immortals connect.', $project->getSummary());
        $this->assertSame('No sunlight. No garlic. Just fangs, friends, and eternal drama. 🦇💬', $project->getDescription());
        $this->assertCount(2, $project->getProjectTechnologies());
        $this->assertProjectTechnology($project, $bloodyDBTechnology, 0);
        $this->assertProjectTechnology($project, $knifeJSTechnology, 1);
        $this->assertCount(1, $project->getProjectTags());
        $this->assertProjectTag($project, $deadSignTag, 0);
        $this->assertCount(1, $project->getCompanies());
        $this->assertSame($corpsCompany->getName(), $project->getCompanies()->first()->getName());
        $this->assertSame(Level::NOOB_QUEST, $project->getLevel());
        $this->assertCount(1, $project->getPictures());
        $picture = $project->getPictures()->first();
        $this->assertSame('ghost_selfie.jpeg', $picture->getPath());
    }

    public function testEditProjectFormRendersCorrectly()
    {
        $technologies = $this->entityManager->getRepository(Technology::class)->findBy([], ['position' => 'ASC']);
        $tags = $this->entityManager->getRepository(Tag::class)->findBy([], ['position' => 'ASC']);
        /** @var Project $project */
        $project = $this->entityManager->getRepository(Project::class)->findOneBy(['title' => 'Haunted house finder']);

        $crawler = $this->client->request('GET', '/backstage/project/edit/' . $project->getId());
        $this->assertResponseIsSuccessful();

        // Check the order of technology options
        $technologySelect = $crawler->filter('select[name="project[technologies][]"]');
        $this->assertCount(1, $technologySelect);

        $options = $technologySelect->filter('option')->each(fn($node) => $node->text());

        $expectedOrder = [];
        $projectTechnologies = $project->getProjectTechnologies()->toArray();
        usort($projectTechnologies, fn($a, $b) => $a->getPosition() <=> $b->getPosition());
        foreach ($projectTechnologies as $projectTechnology) {
            $expectedOrder[] = $projectTechnology->getTechnology()->getName();
        }
        foreach ($technologies as $technology) {
            if (!in_array($technology->getName(), $expectedOrder)) {
                $expectedOrder[] = $technology->getName();
            }
        }

        $this->assertSame($options, $expectedOrder);

        // Check the order of tag options
        $tagSelect = $crawler->filter('select[name="project[tags][]"]');
        $this->assertCount(1, $tagSelect);

        $options = $tagSelect->filter('option')->each(fn($node) => $node->text());

        $expectedOrder = [];
        $projectTags = $project->getProjectTags()->toArray();
        usort($projectTags, fn($a, $b) => $a->getPosition() <=> $b->getPosition());
        foreach ($projectTags as $projectTag) {
            $expectedOrder[] = $projectTag->getTag()->getName();
        }
        foreach ($tags as $tag) {
            if (!in_array($tag->getName(), $expectedOrder)) {
                $expectedOrder[] = $tag->getName();
            }
        }

        $this->assertSame($options, $expectedOrder);

        // Assert that the element with ID "existing-pictures" is present
        $this->assertSelectorExists('#existing-pictures');
    }

    public function testEditProject()
    {
        $screamScriptTechnology = $this->entityManager->getRepository(Technology::class)->findOneBy(['name' => 'ScreamScript']);
        $csslayerTag = $this->entityManager->getRepository(Tag::class)->findOneBy(['name' => 'CSSlayer']);
        $corpsCompany = $this->entityManager->getRepository(Company::class)->findOneBy(['name' => '404 Corps']);
        $project = $this->entityManager->getRepository(Project::class)->findOneBy(['title' => 'Haunted house finder']);
        $fakePictureHandler = static::getContainer()->get(PictureHandler::class);

        $crawler = $this->client->request('GET', '/backstage/project/edit/' . $project->getId());

        // Assert that no calls were made to the FakePictureHandler methods
        $this->assertEquals(0, $fakePictureHandler->uploadProjectPictureCalls);
        $this->assertEquals(0, $fakePictureHandler->deleteProjectPictureCalls);

        $form = $crawler->filter('form[name=project]')->form();
        $form['project[summary]'] = 'An app to locate the creepiest haunted houses everywhere around the world.';
        $form['project[technologies]'] = [$screamScriptTechnology->getId()];
        $form['project[tags]'] = [$csslayerTag->getId()];
        $form['project[companies]'] = [$corpsCompany->getId()];

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/projects');

        // Assert that no calls were made to the FakePictureHandler methods
        $this->assertEquals(0, $fakePictureHandler->uploadProjectPictureCalls);
        $this->assertEquals(0, $fakePictureHandler->deleteProjectPictureCalls);

        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Changes saved! It\'s now 27% better (scientifically proven). 🧠💾');

        /** @var Project $project */
        $project = $this->entityManager->getRepository(Project::class)->findOneBy(['title' => 'Haunted house finder']);
        $this->assertNotNull($project);
        $this->assertSame('Haunted house finder', $project->getTitle());
        $this->assertSame('An app to locate the creepiest haunted houses everywhere around the world.', $project->getSummary());
        $this->assertSame('Warning: we take no responsibility for what happens if you actually visit.', $project->getDescription());
        $this->assertCount(1, $project->getProjectTechnologies());
        $this->assertProjectTechnology($project, $screamScriptTechnology, 0);
        $this->assertCount(1, $project->getProjectTags());
        $this->assertProjectTag($project, $csslayerTag, 0);
        $this->assertCount(1, $project->getCompanies());
        $this->assertSame($corpsCompany->getName(), $project->getCompanies()->first()->getName());
        $this->assertSame(Level::SIDE_QUEST, $project->getLevel());
        $this->assertCount(2, $project->getPictures());
    }

    public function testPreviewProject()
    {
        $project = $this->entityManager->getRepository(Project::class)->findOneBy(['title' => 'Haunted house finder']);

        $this->client->request('GET', '/backstage/project/preview/' . $project->getId());
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('.project-card');
        $this->assertSelectorExists("#detailsModal-{$project->getId()}");
    }

    public function testDeleteProject()
    {
        $project = $this->entityManager->getRepository(Project::class)->findOneBy(['title' => 'Haunted house finder']);
        $id = $project->getId();
        $fakePictureHandler = static::getContainer()->get(PictureHandler::class);

        $this->assertCount(2, $project->getPictures());

        // Assert that no calls were made yet to the FakePictureHandler methods
        $this->assertEquals(0, $fakePictureHandler->uploadProjectPictureCalls);
        $this->assertEquals(0, $fakePictureHandler->deleteProjectPictureCalls);

        $this->client->request('GET', '/backstage/project/delete/' . $id);

        $this->assertResponseRedirects('/backstage/projects');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Project deleted. Ghosted. 👻');

        $deletedProject = $this->entityManager->getRepository(Project::class)->find($id);
        $this->assertNull($deletedProject);

        // Assert that the FakePictureHandler's deleteProjectPicture method was called twice
        $this->assertEquals(0, $fakePictureHandler->uploadProjectPictureCalls);
        $this->assertEquals(2, $fakePictureHandler->deleteProjectPictureCalls);
    }

    public function testDeleteProjectPicture()
    {
        $project = $this->entityManager->getRepository(Project::class)->findOneBy(['title' => 'Haunted house finder']);
        $picture = $project->getPictures()->first();
        $id = $picture->getId();
        $fakePictureHandler = static::getContainer()->get(PictureHandler::class);

        // Assert that no calls were made yet to the FakePictureHandler methods
        $this->assertEquals(0, $fakePictureHandler->uploadProjectPictureCalls);
        $this->assertEquals(0, $fakePictureHandler->deleteProjectPictureCalls);

        $this->client->request('GET', '/backstage/picture/delete/' . $id);

        $this->assertResponseRedirects('/backstage/project/edit/' . $project->getId());
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Poof! The picture\'s gone. No going back now... 🧙️✨');

        $deletedPicture = $this->entityManager->getRepository(Picture::class)->find($id);
        $this->assertNull($deletedPicture);

        // Assert that the FakePictureHandler's deleteProjectPicture method was called once
        $this->assertEquals(0, $fakePictureHandler->uploadProjectPictureCalls);
        $this->assertEquals(1, $fakePictureHandler->deleteProjectPictureCalls);
    }

    private function assertProjectTechnology(Project $project, Technology $technology, int $position): void
    {
        $projectTechnologies = $project->getProjectTechnologies()->filter(function (ProjectTechnology $projectTechnology) use ($technology) {
            return $projectTechnology->getTechnology()->getName() === $technology->getName();
        });

        $this->assertCount(1, $projectTechnologies);
        $this->assertEquals($projectTechnologies->first()->getPosition(), $position);
    }

    private function assertProjectTag(Project $project, Tag $tag, int $position): void
    {
        $projectTags = $project->getProjectTags()->filter(function (ProjectTag $projectTag) use ($tag) {
            return $projectTag->getTag()->getName() === $tag->getName();
        });

        $this->assertCount(1, $projectTags);
        $this->assertEquals($projectTags->first()->getPosition(), $position);
    }
}
