<?php

namespace App\Entity;

/* use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface; */
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 60, nullable: false)]
    private ?string $full_name = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\OneToMany(targetEntity: Address::class, mappedBy: 'user')]
    private Collection $addresses;

    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'author')]
    private Collection $articles;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Profile $profile = null;


    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;


    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->articles = new ArrayCollection();
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Get the value of full_name
     *
     * @return ?string
     */
    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    /**
     * Set the value of full_name
     *
     * @param ?string $full_name
     *
     * @return self
     */
    public function setFullName(?string $full_name): self
    {
        $this->full_name = $full_name;

        return $this;
    }

    /**
     * Get the value of createdAt
     *
     * @return ?\DateTimeImmutable
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @param ?\DateTimeImmutable $createdAt
     *
     * @return self
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    

    /**
     * Get the value of addresses
     *
     * @return Collection
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    /**
     * Set the value of addresses
     *
     * @param Collection $addresses
     *
     * @return self
     */
    public function addAddresses(Address $addresse): self
    {
        if (!$this->addresses->contains($addresse)) {
            $this->addresses->add($addresse);
            $addresse->setUser($this);
        }

        return $this;
    }

    /**
     * Get the value of articles
     *
     * @return Collection
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * Set the value of articles
     *
     * @param Collection $articles
     *
     * @return self
     */
    public function addArticles(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticles(Article $article): self
    {
        if (!$this->articles->removeElement($article)) {
            $this->articles->add($article);
            $article->setAuthor($this);
        }

        return $this;
    }

    /**
     * Get the value of profile
     *
     * @return ?Profile
     */
    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    /**
     * Set the value of profile
     *
     * @param ?Profile $profile
     *
     * @return self
     */
    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }
}
