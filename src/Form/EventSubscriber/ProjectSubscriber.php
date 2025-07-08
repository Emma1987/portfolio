<?php

namespace App\Form\EventSubscriber;

use App\Entity\Project;
use App\Entity\ProjectTag;
use App\Entity\ProjectTechnology;
use App\Entity\Tag;
use App\Entity\Technology;
use App\Storage\PictureHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjectSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PictureHandler $pictureHandler,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    public function onPreSubmit(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        $form = $event->getForm();
        $project = $form->getData();

        $selectedTechnologies = $data['technologies'];
        $this->updateTechnologies($project, $selectedTechnologies);

        $selectedTags = $data['tags'];
        $this->updateTags($project, $selectedTags);
    }

    public function onSubmit(SubmitEvent $event): void
    {
        $project = $event->getData();
        $form = $event->getForm();

        $pictures = $form->get('pictures')->getData();
        $this->updatePictures($project, $pictures);
    }

    /**
     * @param array<int, string> $formTechnologies
     */
    private function updateTechnologies(Project $project, array $formTechnologies): void
    {
        $currentProjectTechnologies = new ArrayCollection($project->getProjectTechnologies()->toArray());

        foreach ($formTechnologies as $position => $formTechnology) {
            $entity = $currentProjectTechnologies->filter(function (ProjectTechnology $projectTechnology) use ($formTechnology) {
                return $projectTechnology->getTechnology()->getId() === (int) $formTechnology;
            })->first();

            if ($entity) {
                $entity->setPosition($position);
            } else {
                $entity = (new ProjectTechnology())
                    ->setProject($project)
                    ->setTechnology($this->entityManager->getReference(Technology::class, (int) $formTechnology))
                    ->setPosition($position)
                ;

                $project->addProjectTechnology($entity);
            }

        }

        foreach ($currentProjectTechnologies as $projectTechnology) {
            if (!in_array($projectTechnology->getTechnology()->getId(), $formTechnologies)) {
                $project->removeProjectTechnology($projectTechnology);
            }
        }
    }

    /**
     * @param array<int, string> $formTags
     */
    private function updateTags(Project $project, array $formTags): void
    {
        $currentProjectTags = new ArrayCollection($project->getProjectTags()->toArray());

        foreach ($formTags as $position => $formTag) {
            $entity = $currentProjectTags->filter(function (ProjectTag $projectTag) use ($formTag) {
                return $projectTag->getTag()->getId() === (int) $formTag;
            })->first();

            if ($entity) {
                $entity->setPosition($position);
            } else {
                $entity = (new ProjectTag())
                    ->setProject($project)
                    ->setTag($this->entityManager->getReference(Tag::class, (int) $formTag))
                    ->setPosition($position)
                ;

                $project->addProjectTag($entity);
            }

        }

        foreach ($currentProjectTags as $projectTag) {
            if (!in_array($projectTag->getTag()->getId(), $formTags)) {
                $project->removeProjectTag($projectTag);
            }
        }
    }

    /**
     * @param array<int, UploadedFile> $pictures
     */
    private function updatePictures(Project $project, array $pictures): void
    {
        foreach ($pictures as $file) {
            $this->pictureHandler->uploadProjectPicture($project, $file);
        }
    }
}
