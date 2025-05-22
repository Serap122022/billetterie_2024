<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity]
#[ORM\Table(name: "users")]
#[ORM\UniqueConstraint(name: "UNIQ_EMAIL", columns: ["email"])]
#[UniqueEntity(
    fields: ['email'],
    message: 'Un compte avec cet email existe déjà.',
    errorPath: 'email'
)]

#[UniqueEntity(
    fields: ['username', 'firstName'],
    message: 'Ce nom et ce prénom sont déjà utilisés.',
    errorPath: 'username'
)]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    #[Assert\NotBlank(message: "Le nom d'utilisateur ne peut pas être vide.")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le nom d'utilisateur doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le nom d'utilisateur ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $username;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide.")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le prénom doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères."
    )]
    private string $firstName;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    #[Assert\NotBlank(message: "L'email ne peut pas être vide.")]
    #[Assert\Email(message: "Le format de l'email est invalide.")]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $password; 
    #[Assert\NotBlank(message: "Le mot de passe ne peut pas être vide.")]
    #[Assert\Length(
        min: 8,
        minMessage: "Le mot de passe doit comporter au moins {{ limit }} caractères."
    )]
    #[Assert\Regex(
        pattern: "/^(?=.*[A-Z])(?=.*\d).{8,}$/",
        message: "Le mot de passe doit contenir au moins une majuscule et un chiffre."
    )]
    private ?string $plainPassword = null;

    #[ORM\Column(type: 'boolean')]
    private $terms = false;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $created_at;

    #[ORM\Column(type: "string", length: 255)]
    private string $user_key; 

    #[ORM\Column(type: "boolean")]
    private bool $is_active = true;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    #[ORM\Column(type: "string", nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $resetTokenExpiresAt = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Panier::class, orphanRemoval: true)]
    private Collection $paniers;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: Orders::class, cascade: ['persist', 'remove'])]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Payment::class, cascade: ['persist', 'remove'])]
    private Collection $paiements;


    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->orders = new ArrayCollection();
        $this->user_key = bin2hex(random_bytes(16));
        $this->roles[] = 'ROLE_USER';
        $this->paniers = new ArrayCollection();
        $this->paiements = new ArrayCollection();
    }

    // =======================
    //  GETTERS & SETTERS
    // =======================

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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getTerms(): ?bool
    {
        return $this->terms;
    }

    public function setTerms(bool $terms): self
    {
        $this->terms = $terms;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

public function getPaniers(): Collection
{
    return $this->paniers;
}

public function setPanier(?Panier $panier): self
    {
        // DéfiniT la relation inverse
        if ($panier && $panier->getUser () !== $this) {
            $panier->setUser ($this);
        }

        $this->panier = $panier;

        return $this;
    }
 
    
public function getPaiements(): Collection
{
    return $this->paiements;
}

public function addPaiement(Payment $paiement): self
{
    if (!$this->paiements->contains($paiement)) {
        $this->paiements[] = $paiement;
        $paiement->setUtilisateur($this);
    }

    return $this;
}

public function removePaiement(Payment $paiement): self
{
    if ($this->paiements->removeElement($paiement)) {
        if ($paiement->getUtilisateur() === $this) {
            $paiement->setUtilisateur(null);
        }
    }

    return $this;
}


    public function getUserKey(): string
    {
        return $this->user_key;
    }

    public function setUserKey(string $userKey): self
    {
        $this->user_key = $userKey;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function setActive(bool $is_active): self
    {
        $this->is_active = $is_active;
        return $this;
    }


 // GETTER & SETTER pour resetToken

 public function getResetToken(): ?string
 {
     return $this->resetToken;
 }

 public function setResetToken(?string $resetToken): self
 {
     $this->resetToken = $resetToken;
     return $this;
 }

 public function getResetTokenExpiresAt(): ?\DateTime
 {
     return $this->resetTokenExpiresAt;
 }

 public function setResetTokenExpiresAt(?\DateTime $resetTokenExpiresAt): self
 {
     $this->resetTokenExpiresAt = $resetTokenExpiresAt;
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

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
        $this->confirmPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    // =======================
    // GESTION DES COMMANDES
    // =======================

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->removeElement($order)) {
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }
}
