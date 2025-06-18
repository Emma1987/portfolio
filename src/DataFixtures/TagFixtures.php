<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public const CSSLAYER_TAG = 'CSSLAYER_TAG';
    public const DEADSIGN_TAG = 'DEADSIGN_TAG';

    public function load(ObjectManager $manager): void
    {
        $csslayer = (new Tag())->setName('CSSlayer')->setPosition(1);
        $this->addReference(self::CSSLAYER_TAG, $csslayer);

        $deadSign = (new Tag())->setName('DeadSign')->setPosition(2);
        $this->addReference(self::DEADSIGN_TAG, $deadSign);

        $manager->persist($csslayer);
        $manager->persist($deadSign);

        $manager->flush();
    }
}
