<?php

namespace App\Entity;

use App\Entity\Enum\ToolCategory;
use App\Entity\Trait\LocaleTrait;
use App\Entity\Trait\NameTrait;
use App\Entity\Trait\PositionTrait;
use App\Repository\ToolRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: ToolRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_TOOL_NAME', fields: ['name'])]
class Tool implements Translatable
{
    use LocaleTrait;
    use NameTrait;
    use PositionTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, enumType: ToolCategory::class)]
    private ?ToolCategory $category = null;

    public function getId(): ?int
    {
        return $this->id;
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
