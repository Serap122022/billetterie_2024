<?php

namespace App\Tests\Controller;

use App\Controller\EvenementsController;
use App\Entity\Evenements;
use App\Repository\EvenementsRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EvenementsControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        // Créer un mock du EvenementsRepository
        $evenementsRepository = $this->createMock(EvenementsRepository::class);

        // Configurer le mock pour retourner une liste d'événements
        $evenements = [
            new Evenements(),
            new Evenements(),
            new Evenements(),
        ];
        $evenementsRepository->expects($this->once())
            ->method('findByFilters')
            ->willReturn($evenements);

        // Créer le contrôleur avec le mock de EvenementsRepository
        $controller = new EvenementsController($evenementsRepository);

        // Appeler la méthode à tester
        $response = $controller->index(new Request(), $evenementsRepository);

        // Vérifier que la réponse est un objet Response
        $this->assertInstanceOf(Response::class, $response);

        // Vérifier que le template correct est rendu
        $this->assertSame('evenements/evenements.html.twig', $response->getTargetTemplate());

        // Vérifier que les variables transmises au template sont correctes
        $this->assertSame($evenements, $response->getParameter('evenements'));
    }

    public function testParticipants()
    {
        $client = static::createClient();

        // Créer le contrôleur
        $controller = new EvenementsController();

        // Appeler la méthode à tester
        $response = $controller->participants(new Request());

        // Vérifier que la réponse est un objet Response
        $this->assertInstanceOf(Response::class, $response);

        // Vérifier que le template correct est rendu
        $this->assertSame('evenements/participantes.html.twig', $response->getTargetTemplate());
    }
}

