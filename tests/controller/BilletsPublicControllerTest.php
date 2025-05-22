<?php

namespace App\Tests\Controller;

use App\Controller\BilletsPublicController;
use App\Entity\Billets;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BilletsPublicControllerTest extends WebTestCase
{
    public function testPublicIndex()
    {
        $client = static::createClient();

        // Créer un mock de l'EntityManagerInterface
        $entityManager = $this->createMock(EntityManagerInterface::class);

        // Configurer le mock pour retourner une liste de billets
        $billets = [
            new Billets(),
            new Billets(),
            new Billets(),
        ];
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(Billets::class)
            ->willReturn($this->createMock(BilletsRepository::class));
        $entityManager->expects($this->once())
            ->method('findAll')
            ->willReturn($billets);

        // Créer le contrôleur avec le mock d'EntityManager
        $controller = new BilletsPublicController($entityManager);

        // Appeler la méthode à tester
        $response = $controller->publicIndex($entityManager);

        // Vérifier que la réponse est un objet Response
        $this->assertInstanceOf(Response::class, $response);

        // Vérifier que le template correct est rendu
        $this->assertSame('billets_public/index.html.twig', $response->getTargetTemplate());

        // Vérifier que les variables transmises au template sont correctes
        $this->assertSame($billets, $response->getParameter('billets'));
        $this->assertSame(count($billets), $response->getParameter('nombreBillets'));
    }
}
