<?php

namespace App\Controller;

use App\Repository\EvenementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EvenementsRepository $evenementsRepository): Response
    {
        // Récupére tous les événements
        $evenements = $evenementsRepository->findAll();

        return $this->render('home/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }
}
