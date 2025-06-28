<?php

namespace App\Entity;

use App\Entity\Trait\LocaleTrait;
use App\Entity\Trait\NameTrait;
use App\Entity\Trait\PositionTrait;
use App\Repository\TechnologyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: TechnologyRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_TECHNOLOGY_NAME', fields: ['name'])]
class Technology implements Translatable
{
    use LocaleTrait;
    use NameTrait;
    use PositionTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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
