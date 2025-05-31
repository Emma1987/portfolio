<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $bugNSlay = (new Company())->setName('Bug & Slay')->setPosition(1);
        $corps = (new Company())->setName('404 Corps')->setPosition(2);

        $manager->persist($bugNSlay);
        $manager->persist($corps);

        $manager->flush();
    }
}
