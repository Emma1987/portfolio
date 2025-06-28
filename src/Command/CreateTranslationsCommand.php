<?php

namespace App\Command;

use App\Service\TranslationService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Parameter;
use Gedmo\Mapping\Driver\AttributeReader;
use Gedmo\Translatable\Entity\Translation;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

#[AsCommand(name: 'app:create-translations', description: 'Creates the missing translations.')]
class CreateTranslationsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PropertyAccessorInterface $propertyAccessor,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $attributeReader = new AttributeReader();
        $io = new SymfonyStyle($input, $output);
        $io->title('Generate translations.');

        // Retrieve all entities and filter those with Translatable annotation
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $translatableEntities = array_filter($metadata, function (ClassMetadata $entity) {
            return in_array(Translatable::class, class_implements($entity->getName()));
        });

        $nbEntities = count($translatableEntities);
        $io->note(sprintf('Will create translations for %d entities.', $nbEntities));

        $count = 0;
        foreach ($translatableEntities as $classMetadataEntity) {
            $reflectionClass = $classMetadataEntity->getReflectionClass();

            // For each property, if property has the Translatable annotation and the translation does not exist, create a translation
            foreach ($reflectionClass->getProperties() as $property) {
                if (empty($attributeReader->getPropertyAnnotation($property, \Gedmo\Mapping\Annotation\Translatable::class))) {
                    continue;
                }

                $missingTranslations = $this->getEntitiesWithMissingTranslations($classMetadataEntity->getName(), $property->getName());
                foreach ($missingTranslations as $missingTranslation) {
                    $translation = (new Translation())
                        ->setObjectClass($classMetadataEntity->getName())
                        ->setField($property->getName())
                        ->setForeignKey($missingTranslation->getId())
                        ->setLocale('fr')
                        ->setContent($this->propertyAccessor->getValue($missingTranslation, $property->getName()))
                    ;

                    $this->entityManager->persist($translation);

                    ++$count;
                }
            }
        }

        $this->entityManager->flush();

        $io->success(sprintf('%d translations were created.', $count));

        return Command::SUCCESS;
    }

    /**
     * @return array<int,object>
     */
    private function getEntitiesWithMissingTranslations(string $objectClass, string $field): array
    {
        return $this->entityManager->getRepository($objectClass) // @phpstan-ignore-line
            ->createQueryBuilder('o')
            ->leftJoin(Translation::class, 't', Join::WITH, 'o.id = t.foreignKey AND t.objectClass = :object_class AND t.field = :field AND t.locale = :locale')
            ->andWhere('t.id IS NULL')
            ->setParameters(new ArrayCollection([
                new Parameter('object_class', $objectClass),
                new Parameter('field', $field),
                new Parameter('locale', TranslationService::FR_TRANS_KEY),
            ]))
            ->getQuery()
            ->getResult()
        ;
    }
}
