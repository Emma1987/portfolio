<?php

namespace App\DataFixtures;

use App\Entity\Enum\ToolCategory;
use App\Entity\Tool;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ToolFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $coffinScript = (new Tool())
            ->setName('Coffin Script')
            ->setCategory(ToolCategory::LANGUAGES)
            ->setPosition(1)
        ;
        $manager->persist($coffinScript);

        $ghoulLang = (new Tool())
            ->setName('Ghoul Lang')
            ->setCategory(ToolCategory::LANGUAGES)
            ->setPosition(2)
        ;
        $manager->persist($ghoulLang);

        $phantomJSon = (new Tool())
            ->setName('Phantom Json')
            ->setCategory(ToolCategory::LANGUAGES)
            ->setPosition(3)
        ;
        $manager->persist($phantomJSon);

        $necroFramework = (new Tool())
            ->setName('Necro Framework')
            ->setCategory(ToolCategory::FRAMEWORKS)
            ->setPosition(1)
        ;
        $manager->persist($necroFramework);

        $werewolfPHP = (new Tool())
            ->setName('Werewolf PHP')
            ->setCategory(ToolCategory::FRAMEWORKS)
            ->setPosition(2)
        ;
        $manager->persist($werewolfPHP);

        $poltergeistRails = (new Tool())
            ->setName('Poltergeist Rails')
            ->setCategory(ToolCategory::FRAMEWORKS)
            ->setPosition(3)
        ;
        $manager->persist($poltergeistRails);

        $chainsawOfDoom = (new Tool())
            ->setName('Chainsaw of Doom')
            ->setCategory(ToolCategory::TOOLS)
            ->setPosition(1)
        ;
        $manager->persist($chainsawOfDoom);

        $coffinNailGun = (new Tool())
            ->setName('Coffin Nail Gun')
            ->setCategory(ToolCategory::TOOLS)
            ->setPosition(2)
        ;
        $manager->persist($coffinNailGun);

        $pairProgramming = (new Tool())
            ->setName('Pair Programming with the Poltergeist')
            ->setCategory(ToolCategory::PRACTICES)
            ->setPosition(1)
        ;
        $manager->persist($pairProgramming);

        $mergeConflict = (new Tool())
            ->setName('The Merge Conflict Massacre')
            ->setCategory(ToolCategory::PRACTICES)
            ->setPosition(2)
        ;
        $manager->persist($mergeConflict);

        $agile = (new Tool())
            ->setName('Agile, but make it cursed')
            ->setCategory(ToolCategory::PRACTICES)
            ->setPosition(3)
        ;
        $manager->persist($agile);

        $manager->flush();
    }
}
