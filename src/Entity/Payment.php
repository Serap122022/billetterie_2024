<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Enum\PaymentStatusEnum;

#[ORM\Entity]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private string $montant;

    #[ORM\Column(type: 'string', enumType: PaymentStatusEnum::class)]
    private PaymentStatusEnum $statut_paiement; // Utilisation de l'énumération pour le statut du paiement

    #[ORM\Column(type: 'string')]
    private string $methode_paiement; // Mode de paiement

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $cle_paiement; // Clé unique pour le paiement

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date_creation;

    #[ORM\ManyToOne(targetEntity: Billets::class, inversedBy: 'paiements')] 
    #[ORM\JoinColumn(nullable: false)]
    private ?Billets $billet = null; // Lien vers l'entité Billet

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'paiements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $utilisateur = null; // L'utilisateur associé au paiement

    // Constructeur
    public function __construct()
    {
        $this->date_creation = new \DateTimeImmutable();
        $this->cle_paiement = bin2hex(random_bytes(16)); // Génére une clé de paiement unique
    }

    // Getters et Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    public function getMethodePaiement(): string
    {
        return $this->methode_paiement;
    }

    public function setMethodePaiement(string $methode_paiement): self
    {
        $this->methode_paiement = $methode_paiement;
        return $this;
    }

    public function getStatutPaiement(): PaymentStatusEnum
    {
        return $this->statut_paiement;
    }

    public function setStatutPaiement(PaymentStatusEnum $statut_paiement): self
    {
        $this->statut_paiement = $statut_paiement;
        return $this;
    }

    public function getDateCreation(): \DateTimeInterface
    {
        return $this->date_creation;
    }

    public function getClePaiement(): string
    {
        return $this->cle_paiement; // Récupére la clé de paiement
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

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur; // Récupére l'utilisateur associé au paiement
    }

    public function setUtilisateur(User $utilisateur): self
    {
        $this->utilisateur = $utilisateur; // Lie l'utilisateur au paiement
        return $this;
    }
}
