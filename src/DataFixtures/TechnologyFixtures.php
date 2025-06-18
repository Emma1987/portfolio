<?php

namespace App\DataFixtures;

use App\Entity\Technology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TechnologyFixtures extends Fixture
{
    public const KNIFE_JS_TECHNOLOGY = 'KNIFE_JS_TECHNOLOGY';
    public const SCREAM_SCRIPT_TECHNOLOGY = 'SCREAM_SCRIPT_TECHNOLOGY';
    public const BLOODY_DB_TECHNOLOGY = 'BLOODY_DB_TECHNOLOGY';

    public function load(ObjectManager $manager): void
    {
        $knifeJs = (new Technology())->setName('Knife.js')->setPosition(1);
        $this->addReference(self::KNIFE_JS_TECHNOLOGY, $knifeJs);

        $screamScript = (new Technology())->setName('ScreamScript')->setPosition(2);
        $this->addReference(self::SCREAM_SCRIPT_TECHNOLOGY, $screamScript);

        $bloodyDB = (new Technology())->setName('BloodyDB')->setPosition(3);
        $this->addReference(self::BLOODY_DB_TECHNOLOGY, $bloodyDB);

        $manager->persist($knifeJs);
        $manager->persist($screamScript);
        $manager->persist($bloodyDB);

        $manager->flush();
    }
}
