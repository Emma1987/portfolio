<?php

namespace App\Repository;

use App\Entity\Tool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tool>
 */
class ToolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tool::class);
    }

    /**
     * @return Tool[]
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.category', 'ASC')
            ->addOrderBy('t.position', 'ASC')
            ->getQuery()
            ->setHint(
                \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\Translatable\Query\TreeWalker\TranslationWalker',
            )
            ->getResult()
        ;
    }
}
