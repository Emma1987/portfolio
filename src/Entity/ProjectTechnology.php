<?php

namespace App\Entity;

use App\Entity\Trait\PositionTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'UNIQ_PROJECT_TECHNOLOGY', columns: ['project_id', 'technology_id'])]
class ProjectTechnology
{
    use PositionTrait;

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
}
