<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @return Project[]
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'p_techno', 'techno', 'p_tag', 'tag')
            ->join('p.projectTechnologies', 'p_techno')
            ->join('p_techno.technology', 'techno')
            ->join('p.projectTags', 'p_tag')
            ->join('p_tag.tag', 'tag')
            ->getQuery()
            ->setHint(
                \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\Translatable\Query\TreeWalker\TranslationWalker',
            )
            ->getResult()
        ;
    }
}
