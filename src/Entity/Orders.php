<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
#[ORM\Table(name: '`orders`')]
#[ORM\HasLifecycleCallbacks]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Admin::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'admin_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?Admin $admin = null;

    #[ORM\OneToMany(targetEntity: OrdersItem::class, mappedBy: 'order', cascade: ['persist', 'remove'])]
    private Collection $orderItems;

    #[ORM\Column(type: 'string', length: 255)]
    private string $orderKey;

    #[ORM\Column(type: 'datetime', options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTimeInterface $orderDate;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank(message: 'Le prix total ne peut pas être vide.')]
    private string $totalPrice;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le prénom ne peut pas être vide.')]
    private string $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le nom ne peut pas être vide.')]
    private string $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'L\'adresse email ne peut pas être vide.')]
    #[Assert\Email(message: 'L\'adresse email {{ value }} n\'est pas valide.')]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'L\'adresse ne peut pas être vide.')]
    private string $address;

    #[ORM\Column(type: 'string', length: 10)]
    #[Assert\NotBlank(message: 'Le code postal ne peut pas être vide.')]
    private string $postalCode;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(message: 'La ville ne peut pas être vide.')]
    private string $city;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(message: 'Le pays ne peut pas être vide.')]
    private string $country;

    #[ORM\Column(type: 'boolean')]
    private bool $isPaid = false; 

    #[ORM\Column(type: 'boolean')]
    private bool $scanned = false;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
        $this->orderDate = new \DateTime();
        $this->isPaid = false;

    }

    #[ORM\PrePersist]
    public function generateOrderKey(): void
    {
        if (empty($this->orderKey)) {
            $this->orderKey = uniqid('order_');
        }
    }

    // Méthodes de gestion de la relation OneToMany avec OrdersItem

    public function addOrderItem(OrdersItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setOrder($this);
        }
        return $this;
    }

    public function removeOrderItem(OrdersItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            if ($orderItem->getOrder() === $this) {
                $orderItem->setOrder(null);
            }
        }
        return $this;
    }

    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    // Getters et setters

    public function getId(): int
    {
        return $this->id;
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
        if ($orderKey === null) {
            throw new \InvalidArgumentException('Order key cannot be null');
        }
        $this->orderKey = $orderKey;
        return $this;
    }

    public function getOrderDate(): \DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(?\DateTimeInterface $orderDate = null): self
    {
        $this->orderDate = $orderDate ?? new \DateTime();
        return $this;
    }    

    public function getTotalPrice(): float
    {
        return (float) $this->totalPrice; 
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = number_format($totalPrice, 2, '.', ''); 
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

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
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

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function isPaid(): bool
    {
        return $this->isPaid;
    }

    public function setPaid(bool $isPaid): self
    {
        $this->isPaid = $isPaid;
        return $this;
    }
    public function isScanned(): bool
{
    return $this->scanned;
}

public function setScanned(bool $scanned): self
{
    $this->scanned = $scanned;
    return $this;
}
public function updateScannedStatus(): void
{
    $allScanned = true;
    foreach ($this->orderItems as $item) {
        if (!$item->isScanned()) {
            $allScanned = false;
            break;
        }
    }
    $this->scanned = $allScanned;
}

}
