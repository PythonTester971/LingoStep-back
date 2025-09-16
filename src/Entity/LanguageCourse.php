<?php

namespace App\Entity;

use App\Enum\LanguageCourseStatus;
use App\Repository\LanguageCourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LanguageCourseRepository::class)]
class LanguageCourse
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

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'languageCourses')]
    private Collection $users;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Language $Language = null;

    /**
     * @var Collection<int, Mission>
     */
    #[ORM\OneToMany(targetEntity: Mission::class, mappedBy: 'languageCourse', orphanRemoval: true)]
    private Collection $missions;

    /**
     * @var Collection<int, UserLanguageCourse>
     */
    #[ORM\OneToMany(targetEntity: UserLanguageCourse::class, mappedBy: 'languageCourse')]
    private Collection $userLanguageCourses;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->missions = new ArrayCollection();
        $this->userLanguageCourses = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->Language;
    }

    public function setLanguage(Language $Language): static
    {
        $this->Language = $Language;

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): static
    {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
            $mission->setLanguageCourse($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): static
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getLanguageCourse() === $this) {
                $mission->setLanguageCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserLanguageCourse>
     */
    public function getUserLanguageCourses(): Collection
    {
        return $this->userLanguageCourses;
    }

    public function addUserLanguageCourse(UserLanguageCourse $userLanguageCourse): static
    {
        if (!$this->userLanguageCourses->contains($userLanguageCourse)) {
            $this->userLanguageCourses->add($userLanguageCourse);
            $userLanguageCourse->setLanguageCourse($this);
        }

        return $this;
    }

    public function removeUserLanguageCourse(UserLanguageCourse $userLanguageCourse): static
    {
        if ($this->userLanguageCourses->removeElement($userLanguageCourse)) {
            // set the owning side to null (unless already changed)
            if ($userLanguageCourse->getLanguageCourse() === $this) {
                $userLanguageCourse->setLanguageCourse(null);
            }
        }

        return $this;
    }
}
