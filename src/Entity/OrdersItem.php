<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrdersItemRepository;
use App\Entity\Orders;
use App\Entity\User;
use App\Entity\Billets;

#[ORM\Entity(repositoryClass: OrdersItemRepository::class)]
#[ORM\Table(name: 'orders_item')]
#[ORM\HasLifecycleCallbacks]
class OrdersItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Orders::class, inversedBy: 'orderItems')]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Orders $order;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Billets::class, inversedBy: 'ordersItems')]
    #[ORM\JoinColumn(name: 'billet_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Billets $billet;    

    #[ORM\Column(type: 'integer')]
    private int $quantite; 

    #[ORM\Column(type: 'string', length: 255)]
    private string $orderKey;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $uniqueTicketKey;

    #[ORM\PrePersist]
    public function generateUniqueTicketKey(): void
    {
        $this->uniqueTicketKey = $this->generateSecureTicketKey();
    }

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $qrCodePath = null;

     // Constructeur pour initialiser User et Order
     public function __construct(User $user, Orders $order)
     {
         $this->user = $user;
         $this->order = $order;
     }
 
    
    // Génération d'une clé sécurisée avec `uniqid()` pour éviter les doublons
    public function generateSecureTicketKey(): string
    {
        if (!$this->user || !$this->order) {
            throw new \LogicException('User and Order must be set before generating the ticket key.');
        }

        // return $this->user->getUserKey() . '-' . $this->order->getOrderKey() . '-' . uniqid();
        return $this->user->getUserKey() . '-' . $this->order->getOrderKey() . '-' . bin2hex(random_bytes(8));
    }
      

    // Getters et setters

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrder(): Orders
    {
        return $this->order;
    }

    public function setOrder(Orders $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getOrderKey(): string
    {
        return $this->orderKey;
    }

    public function setOrderKey(string $orderKey): self
    {
        $this->orderKey = $orderKey;
        return $this;
    }

    public function getUniqueTicketKey(): string
    {
        return $this->uniqueTicketKey;
    }

    public function setUniqueTicketKey(string $uniqueTicketKey): self
    {
        $this->uniqueTicketKey = $uniqueTicketKey;
        return $this;
    }
// Getter et Setter pour qrCodePath
    public function getQrCodePath(): ?string
    {
        return $this->qrCodePath;
    }

    public function setQrCodePath(?string $qrCodePath): self
    {
        $this->qrCodePath = $qrCodePath;
        return $this;
    }
    
    public function getBillet(): Billets
    {
        return $this->billet;
    }

    public function setBillet(Billets $billet): self
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
}
