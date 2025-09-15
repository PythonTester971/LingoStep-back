<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $picturePath = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Language $language = null;

    /**
     * @var Collection<int, LanguageCourse>
     */
    #[ORM\ManyToMany(targetEntity: LanguageCourse::class, mappedBy: 'users')]
    private Collection $languageCourses;

    /**
     * @var Collection<int, AnsweredQuestion>
     */
    #[ORM\OneToMany(targetEntity: AnsweredQuestion::class, mappedBy: 'user')]
    private Collection $answeredQuestions;

    #[ORM\Column(nullable: true)]
    private ?int $xp = null;

    /**
     * @var Collection<int, UserMission>
     */
    #[ORM\OneToMany(targetEntity: UserMission::class, mappedBy: 'user')]
    private Collection $userMissions;

    public function __construct()
    {
        $this->languageCourses = new ArrayCollection();
        $this->answeredQuestions = new ArrayCollection();
        $this->userMissions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPicturePath(): ?string
    {
        return $this->picturePath;
    }

    public function setPicturePath(string $picturePath): static
    {
        $this->picturePath = $picturePath;

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

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): static
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Collection<int, LanguageCourse>
     */
    public function getLanguageCourses(): Collection
    {
        return $this->languageCourses;
    }

    public function addLanguageCourse(LanguageCourse $languageCourse): static
    {
        if (!$this->languageCourses->contains($languageCourse)) {
            $this->languageCourses->add($languageCourse);
            $languageCourse->addUser($this);
        }

        return $this;
    }

    public function removeLanguageCourse(LanguageCourse $languageCourse): static
    {
        if ($this->languageCourses->removeElement($languageCourse)) {
            $languageCourse->removeUser($this);
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
            $answeredQuestion->setUser($this);
        }

        return $this;
    }

    public function removeAnsweredQuestion(AnsweredQuestion $answeredQuestion): static
    {
        if ($this->answeredQuestions->removeElement($answeredQuestion)) {
            // set the owning side to null (unless already changed)
            if ($answeredQuestion->getUser() === $this) {
                $answeredQuestion->setUser(null);
            }
        }

        return $this;
    }

    public function getXp(): ?int
    {
        return $this->xp;
    }

    public function setXp(?int $xp): static
    {
        $this->xp = $xp;
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
            $userMission->setUser($this);
        }

        return $this;
    }

    public function removeUserMission(UserMission $userMission): static
    {
        if ($this->userMissions->removeElement($userMission)) {
            // set the owning side to null (unless already changed)
            if ($userMission->getUser() === $this) {
                $userMission->setUser(null);
            }
        }

        return $this;
    }
}
