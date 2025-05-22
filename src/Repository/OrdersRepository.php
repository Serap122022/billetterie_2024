<?php

namespace App\Repository;

use App\Entity\Orders;
use App\Repository\VentesRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    /**
     * Mise à jour des ventes par type de billet lors de la vente
     *
     * @param string $type
     * @param int $quantity
     */
    public function updateBilletsSale(string $type, int $quantity): void
    {
        // Met à jour les ventes pour le type de billet
        $vente = $this->findVenteByType($type); 
        if ($vente) {
            // Mise à jour des quantités selon le type de billet
            $vente->setVendus($vente->getVendus() + $quantity);
            $this->_em->persist($vente);
            $this->_em->flush();
        }
    }

    /**
     * Méthode pour supprimer une commande et mettre à jour les ventes par type de billet
     */
    public function deleteOrderAndUpdateSales(int $orderId, VentesRepository $ventesRepository): void
    {
        $order = $this->find($orderId);

        if (!$order) {
            throw new \Exception('Commande introuvable.');
        }

        // Suppression de la commande
        $this->_em->remove($order);
        $this->_em->flush();

        // Mettre à jour les ventes
        $this->updateVentesAfterOrderDeletion($order, $ventesRepository);
    }

    /**
     * Récupère la vente par type de billet (par exemple, solo, duo, family)
     */
    private function findVenteByType(string $type)
    {
        return $this->_em->getRepository(Ventes::class)->findOneBy(['type' => $type]);
    }

    /**
     * Mise à jour des ventes après la suppression de la commande
     */
    private function updateVentesAfterOrderDeletion(Orders $order, VentesRepository $ventesRepository): void
    {
        // Exemple pour la logique de mise à jour après suppression d'une commande
        $type = strtolower($order->getType());
        $quantity = $order->getQuantity();

        // Mettre à jour les données de vente
        $ventes = $ventesRepository->find(1); 
        if (!$ventes) {
            throw new \Exception('Données de ventes introuvables.');
        }

        // Mise à jour des ventes
        switch ($type) {
            case 'solo':
                $ventes->setSolo($ventes->getSolo() + $quantity);
                $ventes->setSoloVendus($ventes->getSoloVendus() - $quantity);
                break;
            case 'duo':
                $ventes->setDuo($ventes->getDuo() + $quantity);
                $ventes->setDuoVendus($ventes->getDuoVendus() - $quantity);
                break;
            case 'family':
                $ventes->setFamily($ventes->getFamily() + $quantity);
                $ventes->setFamilyVendus($ventes->getFamilyVendus() - $quantity);
                break;
        }

        $ventes->calculerReste();
        $ventes->calculerVendusTotal();

        $this->_em->persist($ventes);
        $this->_em->flush();
    }
}
