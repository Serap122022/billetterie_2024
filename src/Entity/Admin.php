<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé.')] // Contrainte d'unicité sur la classe
class Admin implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
#[Assert\NotBlank(message: "Le nom d'utilisateur ne peut pas être vide.")]
#[Assert\Length(
    min: 3,
    max: 255,
    minMessage: "Le nom d'utilisateur doit comporter au moins {{ limit }} caractères.",
    maxMessage: "Le nom d'utilisateur ne peut pas dépasser {{ limit }} caractères."
)]
private string $username;

#[ORM\Column(type: 'string', length: 255)]
#[Assert\NotBlank(message: "Le prénom ne peut pas être vide.")]
#[Assert\Length(
    min: 2,
    max: 255,
    minMessage: "Le prénom doit comporter au moins {{ limit }} caractères.",
    maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères."
)]
private string $firstName;

#[ORM\Column(type: 'string', length: 255, unique: true)]
#[Assert\NotBlank(message: "L'email ne peut pas être vide.")]
#[Assert\Email(message: "L'email '{{ value }}' n'est pas un email valide.")]
private string $email;

#[ORM\Column(type: 'string', length: 255)]
#[Assert\NotBlank(message: "Le mot de passe ne peut pas être vide.")]
#[Assert\Length(
    min: 8,
    minMessage: "Le mot de passe doit comporter au moins {{ limit }} caractères."
)]
private string $password;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotNull(message: "L'état actif doit être spécifié.")]
    private bool $active;

    #[ORM\Column(type: 'json')]
    private array $roles = ['ROLE_ADMIN'];

    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: Orders::class)]
    private Collection $orders;

    #[Assert\NotBlank(message: "Le mot de passe en clair ne peut pas être vide lors de la création.")]
    #[Assert\Length(
        min: 8,
        minMessage: "Le mot de passe doit comporter au moins {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
        message: "Le mot de passe doit contenir une majuscule, un chiffre, et un caractère spécial."
    )]
    private ?string $plainPassword = null;

    #[Assert\Length(
        max: 50,
        maxMessage: "Le token de réinitialisation ne peut pas dépasser {{ limit }} caractères."
    )]
    #[ORM\Column(type: 'string', nullable: true)]
private ?string $resetToken = null;


#[Assert\DateTime(message: "La date d'expiration du token de réinitialisation doit être une date valide.")]
#[ORM\Column(type: 'datetime', nullable: true)]
private ?\DateTimeInterface $resetTokenExpiresAt = null;

    // Constructeur
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->active = true;
        $this->orders = new ArrayCollection();
    }

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $isActive): self
    {
        $this->active = $isActive;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }
    
    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setAdmin($this);
        }
        return $this;
    }
    
    public function removeOrder(Orders $order): self
    {
        if ($this->orders->removeElement($order)) {
            if ($order->getAdmin() === $this) {
                $order->setAdmin(null);
            }
        }
        return $this;
    }  

    public function getSalt(): ?string
    {
        return null; 
    }

    public function eraseCredentials(): void
    {
         
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        if ($plainPassword !== null) {
            $this->setPassword(password_hash($plainPassword, PASSWORD_BCRYPT));
        }
        return $this;
    }

    // Getters & Setters pour resetToken
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    public function getResetTokenExpiresAt(): ?\DateTimeInterface
    {
        return $this->resetTokenExpiresAt;
    }

    public function setResetTokenExpiresAt(?\DateTimeInterface $resetTokenExpiresAt): self
    {
        $this->resetTokenExpiresAt = $resetTokenExpiresAt;
        return $this;
    }
}
