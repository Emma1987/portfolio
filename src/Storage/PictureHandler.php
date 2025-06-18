<?php

namespace App\Storage;

use App\Entity\Picture;
use App\Entity\Project;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class PictureHandler
{
    public function __construct(
        #[Autowire('%project_picture_directory%')]
        private string $pictureDirectory,
        private SluggerInterface $slugger,
    ) {}

    public function uploadProjectPicture(Project $project, UploadedFile $file): void
    {
        $extension = $file->getClientOriginalExtension() ?: 'png';
        $newFilename = $this->slugger->slug(mb_substr($project->getTitle(), 0, 50)) . '-' . uniqid() . '.' . $extension;

        try {
            $file->move($this->pictureDirectory, $newFilename);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to move uploaded file: ' . $e->getMessage(), 0, $e);
        }

        $picture = (new Picture())
            ->setProject($project)
            ->setPath($newFilename)
        ;

        $project->addPicture($picture);
    }

    public function deleteProjectPicture(Picture $picture): void
    {
        $filePath = $this->pictureDirectory . '/' . $picture->getPath();
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
