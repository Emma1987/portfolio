<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_TAG_NAME', fields: ['name'])]
class Tag
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

    /** @var Collection<int, ProjectTag> */
    #[ORM\OneToMany(targetEntity: ProjectTag::class, mappedBy: 'tag', cascade: ['remove'])]
    private Collection $projectTags;

    public function __construct()
    {
        $this->projectTags = new ArrayCollection();
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
     * @return Collection<int, ProjectTag>
     */
    public function getProjectTags(): Collection
    {
        return $this->projectTags;
    }

    public function addProjectTag(ProjectTag $tag): static
    {
        if (!$this->projectTags->contains($tag)) {
            $this->projectTags->add($tag);
        }

        return $this;
    }

    public function removeProjectTag(ProjectTag $tag): static
    {
        if ($this->projectTags->removeElement($tag)) {
            $tag->getProject()->removeProjectTag($tag);
        }

        return $this;
    }
}
