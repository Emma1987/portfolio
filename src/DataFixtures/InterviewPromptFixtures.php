<?php

namespace App\DataFixtures;

use App\Entity\Enum\InterviewPromptCategory;
use App\Entity\InterviewPrompt;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InterviewPromptFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $teamHire = (new InterviewPrompt())
            ->setQuestion('If you could hire any monster for your team, who would it be?')
            ->setAnswer('Frankenstein’s monster — excellent at assembling parts into a working whole.')
            ->setCategory(InterviewPromptCategory::PERSONALITY)
        ;
        $manager->persist($teamHire);

        $survivingApocalypse = (new InterviewPrompt())
            ->setQuestion('Where do you see yourself in five years, assuming the world survives?')
            ->setAnswer('Leading a team of survivors building apps for the post-apocalyptic market.')
            ->setCategory(InterviewPromptCategory::AMBITION)
        ;
        $manager->persist($survivingApocalypse);

        $manager->flush();
    }
}
