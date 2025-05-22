<?php

namespace App\Repository;

use App\Entity\Evenements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenements>
 */
class EvenementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenements::class);
    }

    public function findByFilters($search = null, $filterDate = null)
    {
        $qb = $this->createQueryBuilder('e');

        // Filtre par nom de l'événement
        if ($search) {
            $qb->andWhere('e.nomEvenement LIKE :search')
               ->setParameter('search', '%'.$search.'%');
        }

        // Filtre par date (en excluant 2025)
        if ($filterDate) {
            // Converti la date au format 'YYYY-MM-DD' si nécessaire
            $qb->andWhere('e.dateEvenement LIKE :date')
               ->setParameter('date', $filterDate.'%');
        }

        return $qb->getQuery()->getResult();
    }
}
