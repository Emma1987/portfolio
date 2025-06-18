<?php

namespace App\Entity;

use App\Repository\TechnologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TechnologyRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_TECHNOLOGY_NAME', fields: ['name'])]
class Technology
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private int $position = 0;

    /** @var Collection<int, ProjectTechnology> */
    #[ORM\OneToMany(targetEntity: ProjectTechnology::class, mappedBy: 'technology', cascade: ['remove'])]
    private Collection $projectTechnologies;

    public function __construct()
    {
        $this->projectTechnologies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, ProjectTechnology>
     */
    public function getProjectTechnologies(): Collection
    {
        return $this->projectTechnologies;
    }

    public function addProjectTechnology(ProjectTechnology $project): static
    {
        if (!$this->projectTechnologies->contains($project)) {
            $this->projectTechnologies->add($project);
        }

        return $this;
    }

    public function removeProjectTechnology(ProjectTechnology $project): static
    {
        if ($this->projectTechnologies->removeElement($project)) {
            $project->getProject()->removeProjectTechnology($project);
        }

        return $this;
    }
}
