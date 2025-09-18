<?php

namespace App\Entity;

use App\Repository\UserLanguageCourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserLanguageCourseRepository::class)]
class UserLanguageCourse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userLanguageCourses', cascade: ['persist'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userLanguageCourses', cascade: ['persist'])]
    private ?LanguageCourse $languageCourse = null;

    #[ORM\Column]
    private ?int $progress = null;

    /**
     * @var Collection<int, UserMission>
     */
    #[ORM\OneToMany(targetEntity: UserMission::class, mappedBy: 'userLanguageCourse')]
    private Collection $userMission;

    public function __construct()
    {
        $this->userMission = new ArrayCollection();
    }

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

    public function getLanguageCourse(): ?LanguageCourse
    {
        return $this->languageCourse;
    }

    public function setLanguageCourse(?LanguageCourse $languageCourse): static
    {
        $this->languageCourse = $languageCourse;

        return $this;
    }

    public function getProgress(): ?int
    {
        return $this->progress;
    }

    public function setProgress(int $progress): static
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * @return Collection<int, UserMission>
     */
    public function getUserMission(): Collection
    {
        return $this->userMission;
    }

    public function addUserMission(UserMission $userMission): static
    {
        if (!$this->userMission->contains($userMission)) {
            $this->userMission->add($userMission);
            $userMission->setUserLanguageCourse($this);
        }

        return $this;
    }

    public function removeUserMission(UserMission $userMission): static
    {
        if ($this->userMission->removeElement($userMission)) {

            if ($userMission->getUserLanguageCourse() === $this) {
                $userMission->setUserLanguageCourse(null);
            }
        }

        return $this;
    }
}
