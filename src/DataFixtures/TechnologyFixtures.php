<?php

namespace App\DataFixtures;

use App\Entity\Technology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TechnologyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $knifeJs = (new Technology())->setName('Knife.js')->setPosition(1);
        $screamScript = (new Technology())->setName('ScreamScript')->setPosition(2);
        $bloodyDB = (new Technology())->setName('BloodyDB')->setPosition(3);

        $manager->persist($knifeJs);
        $manager->persist($screamScript);
        $manager->persist($bloodyDB);

        $manager->flush();
    }
}
