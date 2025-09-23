<?php

namespace App\Entity;

use App\Repository\AnsweredQuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnsweredQuestionRepository::class)]
class AnsweredQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'answeredQuestions')]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Option::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Option $optione = null;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'answeredQuestions')]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Question $question = null;

    #[ORM\ManyToOne(inversedBy: 'answeredQuestions')]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Mission $mission = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getOptione(): ?Option
    {
        return $this->optione;
    }

    public function setOptione(?Option $optione): static
    {
        $this->optione = $optione;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

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
}
