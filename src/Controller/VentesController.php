<?php

namespace App\Controller;

use App\Entity\Ventes;
use App\Repository\BilletsRepository;
use App\Repository\OrdersItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class VentesController extends AbstractController
{
    private OrdersItemRepository $ordersItemRepository;

    public function __construct(OrdersItemRepository $ordersItemRepository)
    {
        $this->ordersItemRepository = $ordersItemRepository;
    }

    #[Route('/ventes', name: 'ventes')]
    public function index(
        Request $request,
        BilletsRepository $billetsRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $billets = $billetsRepository->findAll();
    
        // Gére la soumission du formulaire
        if ($request->isMethod('POST')) {
            $quantites = $request->get('quantites', []);
    
            foreach ($billets as $billet) {
                $quantiteVendue = isset($quantites[$billet->getId()]) ? (int)$quantites[$billet->getId()] : 0;
    
                if ($quantiteVendue > 0 && $billet->getStock() >= $quantiteVendue) {
                    $billet->setStock($billet->getStock() - $quantiteVendue);
    
                    $vente = new Ventes();
                    $vente->setBillet($billet);
                    $vente->setType($billet->getType());
                    $vente->setQuantite($quantiteVendue);
                    $vente->setPrixTotal($billet->getTarif() * $quantiteVendue);  
                    $vente->addBillets($billet);
    
                    $entityManager->persist($vente);
                } elseif ($quantiteVendue > 0) {
                    $this->addFlash('error', "Stock insuffisant pour le billet {$billet->getType()}.");
                }
            }
    
            try {
                $entityManager->flush();
                $this->addFlash('success', 'Les ventes ont été enregistrées avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
            }
    
            return $this->redirectToRoute('ventes');
        }
    
        // Initialisation des données d'affichage
        $salesData = [];
        $totalStock = $totalVendus = $totalReste = $totalPrixRecupere = 0;
    
        foreach ($billets as $billet) {
            $type = $billet->getType();
            $vendus = 0;
            $prixTotal = 0;
    
            $ventes = $this->ordersItemRepository->findBy(['billet' => $billet]);
    
            foreach ($ventes as $vente) {
                $vendus += $vente->getQuantite();
                $prixTotal += $vente->getQuantite() * $billet->getTarif();  
            }
    
            $reste = $billet->getStock();
    
            $salesData[$type] = [
                'quantiteVendue' => $vendus,
                'montantTotal' => $prixTotal,
                'reste' => $reste,
            ];
    
            $totalStock += $billet->getStock() + $vendus;
            $totalVendus += $vendus;
            $totalReste += $reste;
            $totalPrixRecupere += $prixTotal;
        }
    
        return $this->render('_admin/gestion_billets/ventes.html.twig', [
            'billets' => $billets,
            'salesData' => $salesData,
            'totalStock' => $totalStock,
            'totalVendus' => $totalVendus,
            'totalReste' => $totalReste,
            'totalPrixRecupere' => $totalPrixRecupere,
        ]);
    }       
}
