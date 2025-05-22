<?php

namespace App\Entity;

use App\Repository\EvenementsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementsRepository::class)]
class Evenements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nomEvenement = null;
    
    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateEvenement = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $urlImage = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $logo = null; 

    // Getters et setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEvenement(): ?string
    {
        return $this->nomEvenement;
    }

    public function setNomEvenement(string $nomEvenement): self
    {
        $this->nomEvenement = $nomEvenement;

        return $this;
    }

    public function getDateEvenement(): ?\DateTimeInterface
    {
        return $this->dateEvenement;
    }

    public function setDateEvenement(\DateTimeInterface $dateEvenement): self
    {
        $this->dateEvenement = $dateEvenement;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUrlImage(): ?string
    {
        return $this->urlImage;
    }

    public function setUrlImage(?string $urlImage): self
    {
        $this->urlImage = $urlImage;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    
    // Méthode pour obtenir une version complète des détails de l'événement
    public function getDisplayDetails(): string
    {
        return sprintf(
            '%s - %s (%s)',
            $this->nomEvenement,
            $this->dateEvenement->format('d/m/Y'),
        );
    }
}
