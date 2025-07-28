<?php

namespace App\Repository;

use App\Entity\InterviewPrompt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InterviewPrompt>
 */
class InterviewPromptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InterviewPrompt::class);
    }

    /**
     * @return InterviewPrompt[]
     */
    public function findAll(bool $withHidden = false): array
    {
        $query = $this->createQueryBuilder('ip')
            ->select('ip')
        ;

        if ($withHidden) {
            $query->addOrderBy('ip.hidden', 'ASC');
        } else {
            $query->andWhere('ip.hidden = false');
        }

        return $query
            ->addOrderBy('ip.featured', 'DESC')
            ->addOrderBy('ip.createdAt', 'DESC')
            ->getQuery()
            ->setHint(
                \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                'Gedmo\Translatable\Query\TreeWalker\TranslationWalker',
            )
            ->getResult()
        ;
    }
}
