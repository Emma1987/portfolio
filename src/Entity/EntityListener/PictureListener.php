<?php

namespace App\Entity\EntityListener;

use App\Entity\Picture;
use App\Storage\PictureHandler;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: Picture::class)]
class PictureListener
{
    public function __construct(private PictureHandler $pictureHandler) {}

    public function preRemove(Picture $picture, PreRemoveEventArgs $args): void
    {
        $this->pictureHandler->deleteProjectPicture($picture);
    }
}
