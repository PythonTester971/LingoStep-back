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

    #[ORM\Column]
    private ?int $xpReward = null;

    /**
     * @var Collection<int, UserMission>
     */
    #[ORM\OneToMany(targetEntity: UserMission::class, mappedBy: 'mission')]
    private Collection $userMissions;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->answeredQuestions = new ArrayCollection();
        $this->userMissions = new ArrayCollection();
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

    public function getXpReward(): ?int
    {
        return $this->xpReward;
    }

    public function setXpReward(int $xpReward): static
    {
        $this->xpReward = $xpReward;

        return $this;
    }

    /**
     * @return Collection<int, UserMission>
     */
    public function getUserMissions(): Collection
    {
        return $this->userMissions;
    }

    public function addUserMission(UserMission $userMission): static
    {
        if (!$this->userMissions->contains($userMission)) {
            $this->userMissions->add($userMission);
            $userMission->setMission($this);
        }

        return $this;
    }

    public function removeUserMission(UserMission $userMission): static
    {
        if ($this->userMissions->removeElement($userMission)) {
            // set the owning side to null (unless already changed)
            if ($userMission->getMission() === $this) {
                $userMission->setMission(null);
            }
        }

        return $this;
    }
}
