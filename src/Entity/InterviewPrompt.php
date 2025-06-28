<?php

namespace App\Entity;

use App\Entity\Enum\InterviewPromptCategory;
use App\Repository\InterviewPromptRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InterviewPromptRepository::class)]
class InterviewPrompt implements Translatable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Gedmo\Translatable]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $question = null;

    #[Gedmo\Translatable]
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $answer = null;

    #[ORM\Column(length: 50, nullable: true, enumType: InterviewPromptCategory::class)]
    private ?InterviewPromptCategory $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    public function getCategory(): ?InterviewPromptCategory
    {
        return $this->category;
    }

    public function setCategory(?InterviewPromptCategory $category): static
    {
        $this->category = $category;

        return $this;
    }
}
