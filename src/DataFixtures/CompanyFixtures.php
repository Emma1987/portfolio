<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public const BUG_N_SLAY_COMPANY = 'BUG_N_SLAY_COMPANY';
    public const CORPS_COMPANY = 'CORPS_COMPANY';

    public function load(ObjectManager $manager): void
    {
        $bugNSlay = (new Company())->setName('Bug & Slay')->setPosition(1);
        $this->addReference(self::BUG_N_SLAY_COMPANY, $bugNSlay);

        $corps = (new Company())->setName('404 Corps')->setPosition(2);
        $this->addReference(self::CORPS_COMPANY, $corps);

        $manager->persist($bugNSlay);
        $manager->persist($corps);

        $manager->flush();
    }
}
