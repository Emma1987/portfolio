<?php

namespace App\Entity;

use App\Entity\Trait\LocaleTrait;
use App\Entity\Trait\NameTrait;
use App\Entity\Trait\PositionTrait;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_TAG_NAME', fields: ['name'])]
class Tag implements Translatable
{
    use LocaleTrait;
    use NameTrait;
    use PositionTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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
