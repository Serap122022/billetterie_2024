<?php

namespace App\Controller;

use App\Entity\Billets;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BilletsPublicController extends AbstractController
{
    #[Route('/billets_public', name: 'billets_public')]
    public function publicIndex(EntityManagerInterface $entityManager): Response
    {
        // Récupére tous les billets
        $billets = $entityManager->getRepository(Billets::class)->findAll();
        
        // Compte le nombre de billets
        $nombreBillets = count($billets);
        
        // Rendre le template pour les visiteurs
        return $this->render('billets_public/index.html.twig', [
            'billets' => $billets,
            'nombreBillets' => $nombreBillets, 
        ]);
    }
}
