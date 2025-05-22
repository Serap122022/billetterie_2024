<?php

namespace App\Controller;

use App\Entity\OrdersItem;
use App\Entity\Orders;
use App\Entity\User;
use App\Entity\Billets;
use App\Entity\Panier;
use App\Form\OrdersType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class OrdersController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


#[Route('/orders', name: 'app_order')]
public function create(Request $request, SessionInterface $session, EntityManagerInterface $entityManager): Response
{
    $user = $this->getUser ();

    // Vérifie si l'utilisateur est connecté
    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    // Récupére le panier depuis la session
    $panier = $session->get('panier', []);

    // Vérifie si le panier est vide
    if (empty($panier)) {
        $this->addFlash('error', 'Votre panier est vide.');
        return $this->redirectToRoute('panier_index');
    }

    // Regroupe les éléments du panier
    $groupedItems = [];
    $totalMontant = 0;
    $totalBillets = 0; // Compteur pour le nombre total de billets

    foreach ($panier as $id => $item) {
        $billet = $entityManager->getRepository(Billets::class)->find($id);

        if ($billet && isset($item['quantity'])) {
            $billetType = $billet->getDisplayType();

            if (!isset($groupedItems[$billetType])) {
                $groupedItems[$billetType] = [
                    'quantite' => 0,
                    'prixUnitaire' => $billet->getTarif(),
                    'total' => 0,
                ];
            }

            // Calcule le total
            $groupedItems[$billetType]['quantite'] += $item['quantity'];
            $groupedItems[$billetType]['total'] += $billet->getTarif() * $item['quantity'];
            $totalMontant += $billet->getTarif() * $item['quantity'];
            $totalBillets += $item['quantity']; // Incrémente le compteur total de billets
        }
    }

    // Crée l'objet Orders
    $order = new Orders();
    $order->setEmail($user->getEmail());
    $order->setOrderKey(bin2hex (random_bytes(8)));
    $order->setTotalPrice($totalMontant); 

    // Crée le formulaire de commande
    $form = $this->createForm(OrdersType::class, $order);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $order->setUser ($user);
        $this->entityManager->persist($order);

        // Enregistre chaque billet dans la commande
        foreach ($panier as $id => $item) {
            $billet = $entityManager->getRepository(Billets::class)->find($id);
            if ($billet && isset($item['quantity'])) {
                for ($i = 0; $i < $item['quantity']; $i++) {
                    $ordersItem = new OrdersItem($user, $order);
                    $ordersItem->setOrderKey($order->getOrderKey());
                    $ordersItem->setBillet($billet);
                    $ordersItem->setQuantite(1);

                    // Génére une clé unique pour chaque ticket
                    $uniqueTicketKey = 'ticket_' . bin2hex(random_bytes(8));
                    $secureTicketKey = $user->getUserKey() . $uniqueTicketKey; 
                    $ordersItem->setUniqueTicketKey($secureTicketKey);

                    $this->entityManager->persist($ordersItem);
                }
            }
        }

        // Enregistre en base de données
        $this->entityManager->flush();

        // Stocke l'ID de la commande et d'autres informations dans la session
        $session->set('orderId', $order->getId());
        $session->set('totalPrice', $totalMontant);
        $session->set('totalBillets', $totalBillets);

        // Redirige vers le processus de paiement
        return $this->redirectToRoute('payment');
    }

    return $this->render('orders/index.html.twig', [
        'form' => $form->createView(),
        'groupedItems' => $groupedItems,
        'totalPrice' => $totalMontant,
        'totalBillets' => $totalBillets, 
    ]);
}

}  