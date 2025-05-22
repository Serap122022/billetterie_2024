<?php

namespace App\Controller;

use App\Entity\Evenements;
use App\Repository\EvenementsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementsController extends AbstractController
{
    #[Route('/evenements', name: 'evenements_index')]
    public function index(Request $request, EvenementsRepository $evenementsRepository): Response
    {
        // Récupération des paramètres de recherche et de filtre depuis la requête
        $search = $request->query->get('search');
        $filterDate = $request->query->get('filterDate');

        // Recherche des événements avec les filtres appliqués
        $evenements = $evenementsRepository->findByFilters($search, $filterDate);

        return $this->render('evenements/evenements.html.twig', [
            'evenements' => $evenements,
        ]);
    }
    
    #[Route('/participantes', name: 'participantes_index')]
    public function participants(): Response
    {
        // Ici, vous pouvez récupérer les données des participants si nécessaire
        return $this->render('evenements/participantes.html.twig');
    }
}
