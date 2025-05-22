<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Billets;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Billets::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Billets $billet = null;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;         

    #[ORM\Column(type: 'integer')]
    private int $quantite;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $montant;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        // Initialisation de la date de création
        $this->createdAt = new \DateTime(); // Définit la date de création à la date actuelle
        $this->billets = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getBillet(): ?Billets
    {
        return $this->billet;
    }

    public function setBillet(?Billets $billet): self
    {
        $this->billet = $billet;
        return $this;
    }
    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getMontant(): float
    {
        return $this->billet->getTarif() * $this->quantite;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;
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

    public function getTotalBillets(array $panier): int
    {
        $total = 0;
        foreach ($panier as $item) {
            $total += $item['quantity']; // S'assure que la clé 'quantity' existe
        }
        return $total;
    }
}
