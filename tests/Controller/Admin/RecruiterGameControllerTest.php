<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Enum\InterviewPromptCategory;
use App\Entity\InterviewPrompt;
use App\Tests\AdminWebTestCase;

class RecruiterGameControllerTest extends AdminWebTestCase
{
    public function testRenderIndexIsSuccessful()
    {
        $this->client->request('GET', '/backstage/recruiter-game');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('.question-cards');
    }

    public function testCreateInterviewPrompt(): void
    {
        $crawler = $this->client->request('GET', '/backstage/recruiter-game/add');
        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form[name=interview_prompt]')->form();
        $form['interview_prompt[question]'] = 'What would you do if you discovered your codebase was haunted?';
        $form['interview_prompt[answer]'] = 'First, I\'d check the logs for ghostly stack traces. Then I\'d refactor with garlic and holy water.';
        $form['interview_prompt[category]'] = InterviewPromptCategory::ORGANIZATION->value;

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/recruiter-game');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Perfect 🚀 Now back to pretending to be productive.');

        /** @var InterviewPrompt $question */
        $question = $this->entityManager->getRepository(InterviewPrompt::class)->findOneBy(['question' => 'What would you do if you discovered your codebase was haunted?']);
        $this->assertNotNull($question);
        $this->assertSame('First, I\'d check the logs for ghostly stack traces. Then I\'d refactor with garlic and holy water.', $question->getAnswer());
        $this->assertSame(InterviewPromptCategory::ORGANIZATION, $question->getCategory());
    }

    public function testEditInterviewPrompt()
    {
        $question = $this->entityManager->getRepository(InterviewPrompt::class)->findOneBy(['question' => 'Where do you see yourself in five years, assuming the world survives?']);

        $crawler = $this->client->request('GET', '/backstage/recruiter-game/edit/' . $question->getId());

        $form = $crawler->filter('form[name=interview_prompt]')->form();
        $form['interview_prompt[answer]'] = 'Commanding an army of robotic interns I built myself, ruling over a desolate tech wasteland, and still waiting for that one Jira ticket to be closed.';

        $this->client->submit($form);

        $this->assertResponseRedirects('/backstage/recruiter-game');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Perfect 🚀 Now back to pretending to be productive.');

        $question = $this->entityManager->getRepository(InterviewPrompt::class)->findOneBy(['question' => 'Where do you see yourself in five years, assuming the world survives?']);
        $this->assertSame('Commanding an army of robotic interns I built myself, ruling over a desolate tech wasteland, and still waiting for that one Jira ticket to be closed.', $question->getAnswer());
    }

    public function testDeleteInterviewPrompt()
    {
        $question = $this->entityManager->getRepository(InterviewPrompt::class)->findOneBy(['question' => 'Where do you see yourself in five years, assuming the world survives?']);
        $id = $question->getId();

        $this->client->request('GET', '/backstage/recruiter-game/delete/' . $id);

        $this->assertResponseRedirects('/backstage/recruiter-game');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Data flushed. Like a good cache clear.');

        $deletedQuestion = $this->entityManager->getRepository(InterviewPrompt::class)->find($id);
        $this->assertNull($deletedQuestion);
    }
}
