<?php

namespace App\Form\EventSubscriber;

use App\Service\TranslationService;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Translation\TranslatableMessage;

class TranslationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TranslationService $translationService,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    public function onPreSetData(PreSetDataEvent $event): void
    {
        $translationDTO = $event->getData();
        $form = $event->getForm();

        $frTranslation = isset($translationDTO->translations['fr']) ? $translationDTO->translations['fr']->getContent() : '';
        $translationFieldType = strlen($frTranslation) > 50 ? TextareaType::class : TextType::class;

        $form->add('fr_translation', $translationFieldType, [
            'label' => new TranslatableMessage('translations.form.fr_translation', [], 'admin'),
            'mapped' => false,
            'data' => isset($translationDTO->translations['fr']) ? $translationDTO->translations['fr']->getContent() : '',
        ]);

        $form->add('en_translation', $translationFieldType, [
            'label' => new TranslatableMessage('translations.form.en_translation', [], 'admin'),
            'mapped' => false,
            'required' => false,
            'data' => isset($translationDTO->translations['en']) ? $translationDTO->translations['en']->getContent() : '',
        ]);
    }

    public function onSubmit(SubmitEvent $event): void
    {
        $translationDTO = $event->getData();
        $form = $event->getForm();

        /** @var Translation[] $translations */
        $translations = $translationDTO->translations;
        $frTranslationEntity = $translations[TranslationService::FR_TRANS_KEY];
        $enTranslationEntity = $translations[TranslationService::EN_TRANS_KEY] ?? null;

        $frTranslation = $form->get('fr_translation')->getData();
        if ($frTranslation !== $frTranslationEntity->getContent()) {
            $frTranslationEntity->setContent($frTranslation);
            $this->translationService->updateEntity($frTranslation, $frTranslationEntity);
        }

        $enTranslation = $form->get('en_translation')->getData();
        if (!empty($enTranslation)) {
            if ($enTranslationEntity) {
                if ($enTranslation !== $enTranslationEntity->getContent()) {
                    $enTranslationEntity->setContent($enTranslation);
                }
            } else {
                $translation = (new Translation())
                    ->setObjectClass($frTranslationEntity->getObjectClass())
                    ->setField($frTranslationEntity->getField())
                    ->setForeignKey($frTranslationEntity->getForeignKey())
                    ->setLocale(TranslationService::EN_TRANS_KEY)
                    ->setContent($enTranslation)
                ;

                $this->entityManager->persist($translation);
            }
        }
    }
}
