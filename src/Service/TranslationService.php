<?php

namespace App\Service;

use App\Entity\DTO\TranslationDTO;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;

class TranslationService
{
    public const FR_TRANS_KEY = 'fr';
    public const EN_TRANS_KEY = 'en';

    public function __construct(private EntityManagerInterface $entityManager) {}

    /**
     * @return TranslationDTO[]
     */
    public function getOrderedTranslations(): array
    {
        $dbTranslations = $this->entityManager->getRepository(Translation::class)->findAll();
        $translations = [];

        foreach ($dbTranslations as $dbTranslation) {
            $entityName = $this->getEntityName($dbTranslation->getObjectClass());

            $entityUniqueIdentifier = strtolower($entityName) . '_' . $dbTranslation->getField() . '_' . $dbTranslation->getForeignKey();
            if (!isset($translations[$entityUniqueIdentifier])) {
                $dto = new TranslationDTO();
                $dto->entity = $entityName;
                $dto->class = $dbTranslation->getObjectClass();
                $dto->field = $dbTranslation->getField();
                $dto->foreignKey = $dbTranslation->getForeignKey();

                $translations[$entityUniqueIdentifier] = $dto;
            }

            $translations[$entityUniqueIdentifier]->translations[$dbTranslation->getLocale()] = $dbTranslation;
        }

        sort($translations);

        return $translations;
    }

    public function getEntityName(string $objectClass): string
    {
        $namespace = explode('\\', $objectClass);

        return array_pop($namespace);
    }

    public function updateEntity(string $translation, Translation $translationEntity): void
    {
        $entity = $this->entityManager->getRepository($translationEntity->getObjectClass()) // @phpstan-ignore-line
            ->createQueryBuilder('o')
            ->andWhere('o.id = :foreign_key')
            ->setParameter('foreign_key', $translationEntity->getForeignKey())
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if ($entity) {
            $setter = 'set' . ucfirst($translationEntity->getField());
            $entity->{$setter}($translation);
        }
    }
}
