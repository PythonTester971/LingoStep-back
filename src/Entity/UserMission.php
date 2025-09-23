<?php

namespace App\Entity;

use App\Repository\UserMissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserMissionRepository::class)]
class UserMission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userMissions')]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userMissions')]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Mission $mission = null;

    #[ORM\Column]
    private ?bool $isCompleted = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $completedAt = null;

    #[ORM\Column]
    private ?int $xpObtained = null;

    #[ORM\ManyToOne(inversedBy: 'userMission')]
    #[ORM\JoinColumn(name: "user_language_course_id", referencedColumnName: "id", onDelete: "CASCADE")]
    private ?UserLanguageCourse $userLanguageCourse = null;

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

    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): static
    {
        $this->mission = $mission;

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

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeImmutable $completedAt): static
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    public function getXpObtained(): ?int
    {
        return $this->xpObtained;
    }

    public function setXpObtained(int $xpObtained): static
    {
        $this->xpObtained = $xpObtained;

        return $this;
    }

    public function getUserLanguageCourse(): ?UserLanguageCourse
    {
        return $this->userLanguageCourse;
    }

    public function setUserLanguageCourse(?UserLanguageCourse $userLanguageCourse): static
    {
        $this->userLanguageCourse = $userLanguageCourse;

        return $this;
    }
}
