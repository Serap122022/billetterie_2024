<?php

namespace App\Controller;

use App\Entity\OrdersItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScanController extends AbstractController
{
    #[Route('/scan', name: 'scan_qr')]
    public function scanPage(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si la requête est de type POST
        if ($request->isMethod('POST')) {
            // Récupére la clé unique du QR code
            $uniqueTicketKey = $request->request->get('qrCode');

            if (!$uniqueTicketKey) {
                return new Response("Aucune donnée reçue.", 400);
            }

            // Vérifie si l'item de commande (OrdersItem) existe avec cette clé
            $orderItem = $entityManager->getRepository(OrdersItem::class)->findOneBy(['uniqueTicketKey' => $uniqueTicketKey]);

            if (!$orderItem) {
                return new Response("Billet invalide.", 404);
            }

            // Récupére la commande associée
            $order = $orderItem->getOrder();

            if (!$order) {
                return new Response("Commande introuvable.", 404);
            }

            // Vérifie si le billet a déjà été scanné
            if ($order->isScanned()) {
                return new Response("Billet déjà utilisé.", 403);
            }

            // Marque le billet comme scanné dans la table Orders
            $order->setScanned(true);
            $entityManager->flush();

            // Récupére les détails du billet
            $userEmail = $order->getUser()->getEmail();
            $orderId = $order->getId();
            $totalPrice = $order->getTotalPrice();

            return new Response("Billet valide ! Accès autorisé.\nCommande: #$orderId\nUtilisateur: $userEmail\nPrix total: $totalPrice €", 200);
        }

        // Si la requête est GET, afficher la page
        return $this->render('_employes/gestion_scan/index.html.twig');
    }

    
#[Route('/error', name: 'scan_error')]
public function errorPage(Request $request): Response
{
    // Récupére le message d'erreur de la session
    $errorMessage = $request->getSession()->get('error_message');
    // $request->getSession()->remove('error_message'); 

    return $this->render('_employes/scan/error.html.twig', [
        'errorMessage' => $errorMessage,
    ]);
}
}