<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $isCompleted = null;

    #[ORM\Column]
    private ?bool $isOpen = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'missions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LanguageCourse $languageCourse = null;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'mission')]
    private Collection $questions;

    /**
     * @var Collection<int, AnsweredQuestion>
     */
    #[ORM\OneToMany(targetEntity: AnsweredQuestion::class, mappedBy: 'mission')]
    private Collection $answeredQuestions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->answeredQuestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

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

    public function isCompleted(): ?bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): static
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    public function isOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function setIsOpen(bool $isOpen): static
    {
        $this->isOpen = $isOpen;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLanguageCourse(): ?LanguageCourse
    {
        return $this->languageCourse;
    }

    public function setLanguageCourse(?LanguageCourse $languageCourse): static
    {
        $this->languageCourse = $languageCourse;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setMission($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getMission() === $this) {
                $question->setMission(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AnsweredQuestion>
     */
    public function getAnsweredQuestions(): Collection
    {
        return $this->answeredQuestions;
    }

    public function addAnsweredQuestion(AnsweredQuestion $answeredQuestion): static
    {
        if (!$this->answeredQuestions->contains($answeredQuestion)) {
            $this->answeredQuestions->add($answeredQuestion);
            $answeredQuestion->setMission($this);
        }

        return $this;
    }

    public function removeAnsweredQuestion(AnsweredQuestion $answeredQuestion): static
    {
        if ($this->answeredQuestions->removeElement($answeredQuestion)) {
            // set the owning side to null (unless already changed)
            if ($answeredQuestion->getMission() === $this) {
                $answeredQuestion->setMission(null);
            }
        }

        return $this;
    }
}
