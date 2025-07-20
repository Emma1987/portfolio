<?php

namespace App\Entity;

use App\Entity\Enum\Level;
use App\Entity\Trait\LocaleTrait;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project implements Translatable
{
    use LocaleTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Gedmo\Translatable]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $title = null;

    #[Gedmo\Translatable]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $summary = null;

    #[Gedmo\Translatable]
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $description = null;

    #[ORM\Column(type: Types::STRING, length: 50, enumType: Level::class)]
    private ?Level $level = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'boolean', options: ['default' => 0])]
    private bool $featured = false;

    #[ORM\Column(type: 'boolean', options: ['default' => 0])]
    private bool $hidden = false;

    /** @var Collection<int, ProjectTechnology> */
    #[ORM\OneToMany(targetEntity: ProjectTechnology::class, mappedBy: 'project', cascade: ['all'], orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $projectTechnologies;

    /** @var Collection<int, Company> */
    #[ORM\ManyToMany(targetEntity: Company::class, inversedBy: 'projects', cascade: ['persist'])]
    private Collection $companies;

    /** @var Collection<int, ProjectTag> */
    #[ORM\OneToMany(targetEntity: ProjectTag::class, mappedBy: 'project', cascade: ['persist'], orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $projectTags;

    /** @var Collection<int, Picture> */
    #[ORM\OneToMany(targetEntity: Picture::class, mappedBy: 'project', cascade: ['persist'], orphanRemoval: true)]
    private Collection $pictures;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->projectTechnologies = new ArrayCollection();
        $this->companies = new ArrayCollection();
        $this->projectTags = new ArrayCollection();
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured): static
    {
        $this->featured = $featured;

        return $this;
    }

    public function isHidden(): ?bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): static
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * @return Collection<int, ProjectTechnology>
     */
    public function getProjectTechnologies(): Collection
    {
        return $this->projectTechnologies;
    }

    public function addProjectTechnology(ProjectTechnology $technology): static
    {
        if (!$this->projectTechnologies->contains($technology)) {
            $this->projectTechnologies->add($technology);
            $technology->setProject($this);
        }

        return $this;
    }

    public function removeProjectTechnology(ProjectTechnology $technology): static
    {
        $this->projectTechnologies->removeElement($technology);

        return $this;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): static
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
            $company->addProject($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): static
    {
        $this->companies->removeElement($company);

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
            $tag->setProject($this);
        }

        return $this;
    }

    public function removeProjectTag(ProjectTag $tag): static
    {
        $this->projectTags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): static
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setProject($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): static
    {
        if ($this->pictures->removeElement($picture)) {
            if ($picture->getProject() === $this) {
                $picture->setProject(null);
            }
        }

        return $this;
    }
}
