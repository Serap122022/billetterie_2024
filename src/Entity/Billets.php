<?php

namespace App\Entity;

use App\Repository\BilletsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: BilletsRepository::class)]
class Billets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $type = null;

    #[ORM\Column(type: 'decimal', precision: 6, scale: 2)]
    private ?float $tarif;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $customType = null;

    #[ORM\Column(type: 'integer')]
    private int $stock = 0;

    #[ORM\OneToMany(mappedBy: 'billet', targetEntity: Payment::class, cascade: ['persist', 'remove'])]
    private Collection $paiements;

    #[ORM\OneToMany(mappedBy: 'billet', targetEntity: OrdersItem::class, cascade: ['persist', 'remove'])]
    private Collection $ordersItems;

    public function __construct()
{
    $this->paiements = new ArrayCollection();
    $this->ordersItems = new ArrayCollection();
}

public function updateStock(int $quantite): void
{
    if ($this->stock >= $quantite) {
        $this->stock -= $quantite;
    } else {
        throw new \Exception("Stock insuffisant.");
    }
}

    // Getters et setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTarif(): float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getCustomType(): ?string
    {
        return $this->customType;
    }

    public function setCustomType(?string $customType): self
    {
        $this->customType = $customType;

        return $this;
    }

    
    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;
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
        $paiement->setBillet($this);
    }

    return $this;
}

public function removePaiement(Payment $paiement): self
{
    if ($this->paiements->removeElement($paiement)) {
        if ($paiement->getBillet() === $this) {
            $paiement->setBillet(null);
        }
    }

    return $this;
}

public function getOrdersItems(): Collection
{
    return $this->ordersItems;
}

// Ajouter un OrdersItem
public function addOrdersItem(OrdersItem $ordersItem): self
{
    if (!$this->ordersItems->contains($ordersItem)) {
        $this->ordersItems[] = $ordersItem;
        $ordersItem->setBillet($this);
    }

    return $this;
}

// Supprimer un OrdersItem
public function removeOrdersItem(OrdersItem $ordersItem): self
{
    if ($this->ordersItems->removeElement($ordersItem)) {
        if ($ordersItem->getBillet() === $this) {
            $ordersItem->setBillet(null);
        }
    }

    return $this;
}
    // Méthode pour obtenir le type final (standard ou personnalisé)
    public function getDisplayType(): string
    {
        return $this->type === 'other' && $this->customType ? $this->customType : $this->type;
    }
}
