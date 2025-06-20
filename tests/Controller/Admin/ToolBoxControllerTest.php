<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Enum\ToolCategory;
use App\Entity\Tool;
use App\Tests\AdminWebTestCase;

class ToolBoxControllerTest extends AdminWebTestCase
{
    public function testRenderToolBoxIndexIsSuccessful()
    {
        $this->client->request('GET', '/backstage/toolbox');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('.toolbox-card');
        $this->assertSelectorExists('form[name=tool]');
        $this->assertSelectorExists('form[name=sort_languages]');
        $this->assertSelectorExists('form[name=sort_frameworks]');
        $this->assertSelectorExists('form[name=sort_tools]');
        $this->assertSelectorExists('form[name=sort_practices]');
    }

    public function testAddTool(): void
    {
        $savedTools = $this->entityManager->getRepository(Tool::class)->count();
        $crawler = $this->client->request('GET', '/backstage/toolbox');

        $form = $crawler->filter('form[name=tool]')->form();
        $form['tool[name]'] = 'CreepPress';
        $form['tool[category]'] = ToolCategory::FRAMEWORKS->value;

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/toolbox');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'New tool "CreepPress" created!');

        $tool = $this->entityManager->getRepository(Tool::class)->findOneBy(['name' => 'CreepPress']);
        $this->assertNotNull($tool);
        $this->assertSame(ToolCategory::FRAMEWORKS, $tool->getCategory());
        $this->assertSame($savedTools + 1, $tool->getPosition());
    }

    public function testAddAlreadyExistingTechnology(): void
    {
        $tools = $this->entityManager->getRepository(Tool::class)->findAll();
        $existingTool = array_shift($tools);

        $crawler = $this->client->request('GET', '/backstage/toolbox');

        $form = $crawler->filter('form[name=tool]')->form();
        $form['tool[name]'] = $existingTool->getName();
        $form['tool[category]'] = ToolCategory::LANGUAGES->value;

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/toolbox');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-danger');
        $this->assertSelectorTextContains('.alert-danger', 'Don\'t remember you already created this one?');
    }

    public function testSortTools(): void
    {
        $categories = [ToolCategory::LANGUAGES, ToolCategory::FRAMEWORKS, ToolCategory::TOOLS, ToolCategory::PRACTICES];
        $formNames = [
            ToolCategory::LANGUAGES->value => 'sort_languages',
            ToolCategory::FRAMEWORKS->value => 'sort_frameworks',
            ToolCategory::TOOLS->value => 'sort_tools',
            ToolCategory::PRACTICES->value => 'sort_practices',
        ];

        foreach ($categories as $category) {
            $formName = $formNames[$category->value];
            $tools = $this->entityManager->getRepository(Tool::class)->findBy(['category' => $category]);

            $crawler = $this->client->request('GET', '/backstage/toolbox');

            $form = $crawler->filter('form[name=' . $formName . ']')->form();
            $formValues = [];
            foreach ($tools as $tool) {
                $key = $tool->getPosition() - 1;
                $this->assertSelectorExists("input[name='{$formName}[tools][{$key}][name]']");
                $this->assertSelectorExists("input[name='{$formName}[tools][{$key}][position]']");

                $this->assertSame($tool->getName(), $form[$formName . '[tools][' . $key . '][name]']->getValue());
                $this->assertEquals($tool->getPosition(), $form[$formName . '[tools][' . $key . '][position]']->getValue());

                $formValues[$key] = $tool;
            }

            $newCount = 1;
            foreach ($formValues as $key => $formValue) {
                $form[$formName . '[tools][' . $key . '][position]'] = $newCount;
                ++$newCount;
            }

            $this->client->submit($form);

            $this->assertResponseRedirects('/backstage/toolbox');
            $this->client->followRedirect();

            $this->assertSelectorExists('.alert-success');
            $this->assertSelectorTextContains('.alert-success', 'You just did that. Like a boss.');

            $newPosition = 1;
            foreach ($tools as $tool) {
                $updatedTool = $this->entityManager->getRepository(Tool::class)->find($tool->getId());
                $this->assertEquals($updatedTool->getPosition(), $newPosition);
                ++$newPosition;
            }
        }
    }

    public function testDeleteTool(): void
    {
        $tools = $this->entityManager->getRepository(Tool::class)->findAll();
        $existingTool = array_shift($tools);
        $id = $existingTool->getId();

        $this->client->request('GET', "/backstage/tool/delete/{$id}");

        $this->assertResponseRedirects('/backstage/toolbox');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Data flushed. Like a good cache clear.');

        $deletedTool = $this->entityManager->getRepository(Tool::class)->find($id);
        $this->assertNull($deletedTool);
    }
}
