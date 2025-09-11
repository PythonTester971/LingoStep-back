<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $instruction = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isAnswered = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mission $mission = null;

    /**
     * @var Collection<int, Option>
     */
    #[ORM\OneToMany(targetEntity: Option::class, mappedBy: 'question')]
    private Collection $options;

    #[ORM\OneToOne(mappedBy: 'question', cascade: ['persist', 'remove'])]
    private ?AnsweredQuestion $answeredQuestion = null;

    public function __construct()
    {
        $this->options = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    public function setInstruction(string $instruction): static
    {
        $this->instruction = $instruction;

        return $this;
    }

    public function isAnswered(): ?bool
    {
        return $this->isAnswered;
    }

    public function setIsAnswered(?bool $isAnswered): static
    {
        $this->isAnswered = $isAnswered;

        return $this;
    }

    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): static
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): static
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
            $option->setQuestion($this);
        }

        return $this;
    }

    public function removeOption(Option $option): static
    {
        if ($this->options->removeElement($option)) {
            // set the owning side to null (unless already changed)
            if ($option->getQuestion() === $this) {
                $option->setQuestion(null);
            }
        }

        return $this;
    }

    public function getAnsweredQuestion(): ?AnsweredQuestion
    {
        return $this->answeredQuestion;
    }

    public function setAnsweredQuestion(?AnsweredQuestion $answeredQuestion): static
    {
        // unset the owning side of the relation if necessary
        if ($answeredQuestion === null && $this->answeredQuestion !== null) {
            $this->answeredQuestion->setQuestion(null);
        }

        // set the owning side of the relation if necessary
        if ($answeredQuestion !== null && $answeredQuestion->getQuestion() !== $this) {
            $answeredQuestion->setQuestion($this);
        }

        $this->answeredQuestion = $answeredQuestion;

        return $this;
    }
}
