<?php

namespace App\Repository;

use App\Entity\OrdersItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrdersItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrdersItem::class);
    }

    /**
     * Récupère les ventes groupées par type de billet avec quantité totale vendue et montant total.
     *
     * @return array
     */
    public function billetSalesData(): array
    {
        return $this->createQueryBuilder('oi')
            ->select('b.type AS type, SUM(oi.quantite) AS totalVendus, SUM(oi.quantite * b.tarif) AS prixTotal')
            ->join('oi.billet', 'b')
            ->groupBy('b.type')
            ->getQuery()
            ->getResult();
    }
}
