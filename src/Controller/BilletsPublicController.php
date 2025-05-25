<?php

namespace App\Controller;

use App\Entity\Billets;
use App\Entity\OrdersItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BilletsPublicController extends AbstractController
{
    #[Route('/billets_public', name: 'billets_public')]
    public function publicIndex(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les billets
        $billets = $entityManager->getRepository(Billets::class)->findAll();

        // Récupérer tous les items des commandes payées
        $qb = $entityManager->createQueryBuilder();
        $qb->select('oi')
            ->from(OrdersItem::class, 'oi')
            ->join('oi.order', 'o')
            ->where('o.isPaid = true');

        $ordersItemsPayes = $qb->getQuery()->getResult();

        // Calcul des quantités vendues par billet (on compte les billets, pas les personnes)
        $quantitesVendues = [];
        foreach ($ordersItemsPayes as $item) {
            $billetId = $item->getBillet()->getId();
            $quantite = $item->getQuantite();

            if (!isset($quantitesVendues[$billetId])) {
                $quantitesVendues[$billetId] = 0;
            }

            $quantitesVendues[$billetId] += $quantite;
        }

        // Création de l'affichage avec stock restant
        $billetsAvecStock = [];
        foreach ($billets as $billet) {
            $vendus = $quantitesVendues[$billet->getId()] ?? 0;
            $stockRestant = $billet->getStock() - $vendus;

            $billetsAvecStock[] = [
                'id' => $billet->getId(),
                'type' => $billet->getType(),
                'tarif' => $billet->getTarif(),
                'stock' => max(0, $stockRestant), 
            ];
        }

        return $this->render('billets_public/index.html.twig', [
            'billets' => $billetsAvecStock,
        ]);
    }
}
