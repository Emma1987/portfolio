<?php

namespace App\Entity;

use App\Entity\Enum\ToolCategory;
use App\Repository\ToolRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ToolRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_TOOL_NAME', fields: ['name'])]
class Tool
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private int $position = 0;

    #[ORM\Column(length: 50, enumType: ToolCategory::class)]
    private ?ToolCategory $category = null;

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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getCategory(): ?ToolCategory
    {
        return $this->category;
    }

    public function setCategory(ToolCategory $category): static
    {
        $this->category = $category;

        return $this;
    }
}
