<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Enum\Level;
use App\Entity\Picture;
use App\Entity\Project;
use App\Entity\ProjectTag;
use App\Entity\ProjectTechnology;
use App\Entity\Tag;
use App\Entity\Technology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            TechnologyFixtures::class,
            TagFixtures::class,
            CompanyFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $hauntedHouseFinderBloodyDBTechnology = (new ProjectTechnology())
            ->setTechnology($this->getReference(TechnologyFixtures::BLOODY_DB_TECHNOLOGY, Technology::class))
            ->setPosition(1)
        ;
        $hauntedHouseFinderKnifeJSTechnology = (new ProjectTechnology())
            ->setTechnology($this->getReference(TechnologyFixtures::KNIFE_JS_TECHNOLOGY, Technology::class))
            ->setPosition(2)
        ;
        $hauntedHouseFinderDeadsignTag = (new ProjectTag())
            ->setTag($this->getReference(TagFixtures::DEADSIGN_TAG, Tag::class))
            ->setPosition(1)
        ;

        $hauntedHouseFinder = (new Project())
            ->setTitle('Haunted house finder')
            ->setSummary('An app to locate the creepiest haunted houses near you.')
            ->setDescription('Warning: we take no responsibility for what happens if you actually visit.')
            ->addProjectTechnology($hauntedHouseFinderBloodyDBTechnology)
            ->addProjectTechnology($hauntedHouseFinderKnifeJSTechnology)
            ->addProjectTag($hauntedHouseFinderDeadsignTag)
            ->addCompany($this->getReference(CompanyFixtures::BUG_N_SLAY_COMPANY, Company::class))
            ->setLevel(Level::SIDE_QUEST)
            ->addPicture((new Picture())->setPath('haunted_house_finder.png'))
            ->addPicture((new Picture())->setPath('haunted_house_example.png'))
        ;

        $manager->persist($hauntedHouseFinder);

        $manager->flush();
    }
}
