<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'UNIQ_PROJECT_TECHNOLOGY', columns: ['project_id', 'technology_id'])]
class ProjectTechnology
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'projectTechnologies')]
    #[ORM\JoinColumn(nullable: false)]
    private Project $project;

    #[ORM\ManyToOne(targetEntity: Technology::class, fetch: 'EAGER', inversedBy: 'projectTechnologies')]
    #[ORM\JoinColumn(nullable: false)]
    private Technology $technology;

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

    public function getTechnology(): Technology
    {
        return $this->technology;
    }

    public function setTechnology(Technology $technology): static
    {
        $this->technology = $technology;

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
