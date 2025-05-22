<?php

namespace App\Tests\Controller;

use App\Controller\HomeController;
use App\Repository\EvenementsRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        // Créer un mock du EvenementsRepository
        $evenementsRepository = $this->createMock(EvenementsRepository::class);

        // Créer le contrôleur avec le mock de EvenementsRepository
        $controller = new HomeController($evenementsRepository);

        // Appeler la méthode à tester
        $response = $controller->index($evenementsRepository);

        // Vérifier que la réponse est un objet Response
        $this->assertInstanceOf(Response::class, $response);

        // Vérifier que le template correct est rendu
        $this->assertSame('home/index.html.twig', $response->getTargetTemplate());
    }

    public function testAbout()
    {
        $client = static::createClient();

        // Créer le contrôleur
        $evenementsRepository = $this->createMock(EvenementsRepository::class);
        $controller = new HomeController($evenementsRepository);

        // Appeler la méthode à tester
        $response = $controller->about();

        // Vérifier que la réponse est un objet Response
        $this->assertInstanceOf(Response::class, $response);

        // Vérifier que le template correct est rendu
        $this->assertSame('home/about.html.twig', $response->getTargetTemplate());
    }
}
