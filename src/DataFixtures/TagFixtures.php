<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $csslayer = (new Tag())->setName('CSSlayer')->setPosition(1);
        $deadSign = (new Tag())->setName('DeadSign')->setPosition(2);

        $manager->persist($csslayer);
        $manager->persist($deadSign);

        $manager->flush();
    }
}
