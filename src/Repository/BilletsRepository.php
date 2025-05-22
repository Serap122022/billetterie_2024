<?php

namespace App\Repository;

use App\Entity\Billets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Billets>
 */
class BilletsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Billets::class);
    }

    /**
     * Récupère les données de vente pour un billet spécifique.
     *
     * @param Billets $billet
     * @return array
     */
    public function getSalesDataByBillet(Billets $billet): array
    {
        return $this->createQueryBuilder('b')
            ->select('COALESCE(SUM(o.quantite), 0) AS quantiteVendue,
                      COALESCE(SUM(o.quantite * b.tarif), 0) AS montantTotal')
            ->leftJoin('b.ordersItems', 'o') // Jointure entre Billets et OrdersItems
            ->where('b.id = :billetId')
            ->setParameter('billetId', $billet->getId())
            ->getQuery()
            ->getOneOrNullResult(); // Utilisation de getOneOrNullResult() pour éviter les exceptions
    }

    /**
     * Récupère les statistiques de vente par type de billet.
     *
     * @return array
     */
    public function getSalesStatistics(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.type AS type,
                      b.stock AS stock,
                      COALESCE(SUM(o.quantite), 0) AS quantiteVendue,
                      (b.stock - COALESCE(SUM(o.quantite), 0)) AS reste,
                      COALESCE(SUM(o.quantite * b.tarif), 0) AS montantTotal')
            ->leftJoin('b.ordersItems', 'o') // Jointure entre Billets et OrdersItems
            ->groupBy('b.id')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupèr les statistiques de vente par type de billet, avec une ligne "Total".
     *
     * @return array
     */
    public function getSalesStatisticsWithTotal(): array
    {
        // Récupére les statistiques de base
        $stats = $this->getSalesStatistics();

        $totalStock = 0;
        $totalVendus = 0;
        $totalReste = 0;
        $totalMontant = 0.0;

        // Calcule les totaux
        foreach ($stats as $row) {
            $totalStock += $row['stock'];
            $totalVendus += $row['quantiteVendue'];
            $totalReste += $row['reste'];
            $totalMontant += $row['montantTotal'];
        }

        // Ajoute la ligne "Total"
        $stats[] = [
            'type' => 'Total',
            'stock' => $totalStock,
            'quantiteVendue' => $totalVendus,
            'reste' => $totalReste,
            'montantTotal' => $totalMontant,
        ];

        return $stats;
    }

    /**
     * Récupère les statistiques détaillées de vente par billet avec les informations de commande.
     *
     * @return array
     */
    public function getDetailedSalesData(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.type AS type,
                      b.stock AS stock,
                      COALESCE(SUM(o.quantite), 0) AS quantiteVendue,
                      (b.stock - COALESCE(SUM(o.quantite), 0)) AS reste,
                      COALESCE(SUM(o.quantite * b.tarif), 0) AS montantTotal,
                      SUM(o.quantite) AS billetsVendus,
                      SUM(o.quantite * o.totalPrice) AS totalPrice')
            ->leftJoin('b.ordersItems', 'o') // Jointure entre Billets et OrdersItems
            ->groupBy('b.id') // Groupement par type de billet
            ->getQuery()
            ->getResult();
    }
}