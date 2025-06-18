<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'UNIQ_PROJECT_TAG', columns: ['project_id', 'tag_id'])]
class ProjectTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'projectTags')]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    #[ORM\ManyToOne(targetEntity: Tag::class, fetch: 'EAGER', inversedBy: 'projectTags')]
    #[ORM\JoinColumn(nullable: false)]
    private Tag $tag;

    #[ORM\Column]
    private int $position = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getTag(): Tag
    {
        return $this->tag;
    }

    public function setTag(Tag $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }
}
