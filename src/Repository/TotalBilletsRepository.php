<?php

namespace App\Repository;

use App\Entity\TotalBillets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TotalBillets>
 */
class TotalBilletsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TotalBillets::class);
    }

    public function updateBilletsSale(int $type, int $quantite, float $prixUnitaire): void
    {
        $billets = $this->find(1); 

        if ($type == 1) { // Solo
            $billets->setSolo($billets->getSolo() + $quantite);
        } elseif ($type == 2) { // Duo
            $billets->setDuo($billets->getDuo() + $quantite);
        } elseif ($type == 3) { // Family
            $billets->setFamily($billets->getFamily() + $quantite);
        }

        // Met à jour le nombre total de billets vendus
        $billets->setVendus($billets->getVendus() + $quantite);
        // Met à jour le prix total récupéré
        $billets->setPrixTotalRecuperes($billets->getPrixTotalRecuperes() + ($quantite * $prixUnitaire));

        $this->_em->persist($billets);
        $this->_em->flush();
    }
}