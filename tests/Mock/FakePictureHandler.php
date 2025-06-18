<?php

namespace App\Tests\Mock;

use App\Entity\Picture;
use App\Entity\Project;
use App\Storage\PictureHandler;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FakePictureHandler extends PictureHandler
{
    public int $uploadProjectPictureCalls = 0;
    public int $deleteProjectPictureCalls = 0;

    public function uploadProjectPicture(Project $project, UploadedFile $file): void
    {
        $extension = $file->getClientOriginalExtension() ?: 'png';
        $newFilename = 'ghost_selfie.' . $extension;

        $picture = (new Picture())
            ->setProject($project)
            ->setPath($newFilename)
        ;

        $project->addPicture($picture);

        ++$this->uploadProjectPictureCalls;
    }

    public function deleteProjectPicture(Picture $picture): void
    {
        ++$this->deleteProjectPictureCalls;
    }
}
