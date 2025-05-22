<?php

namespace App\Repository;

use App\Entity\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Panier>
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    public function getTotalBillets(): int
{
    $total = 0;
    $panierItems = $this->findAll(); // Récupère tous les éléments du panier
    foreach ($panierItems as $item) {
        $total += $item->getQuantite();
    }
    return $total;
}

    public function addToPanier(array &$panier, $ticketId, $quantity): void
    {
        if (!isset($panier[$ticketId])) {
            $panier[$ticketId] = ['quantity' => 0]; // Initialise si pas encore présent
        }
        $panier[$ticketId]['quantity'] += $quantity; // Ajoute la quantité
    }

    public function removeFromPanier(array &$panier, $ticketId): void
    {
        if (isset($panier[$ticketId])) {
            unset($panier[$ticketId]); // Supprime le billet du panier
        }
    }
}