<?php

namespace App\Tests\Controller;

use App\Controller\EmployesController;
use App\Entity\Employes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmployesControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        // Créer un mock de l'EntityManagerInterface
        $entityManager = $this->createMock(EntityManagerInterface::class);

        // Configurer le mock pour retourner une liste d'employés
        $employes = [
            new Employes(),
            new Employes(),
            new Employes(),
        ];
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->with(Employes::class)
            ->willReturn($this->createMock(EmployesRepository::class));
        $entityManager->expects($this->once())
            ->method('findAll')
            ->willReturn($employes);

        // Créer le contrôleur avec le mock d'EntityManager
        $controller = new EmployesController($entityManager);

        // Appeler la méthode à tester
        $response = $controller->index($entityManager);

        // Vérifier que la réponse est un objet Response
        $this->assertInstanceOf(Response::class, $response);

        // Vérifier que le template correct est rendu
        $this->assertSame('_employes/gestion_scan/index.html.twig', $response->getTargetTemplate());

        // Vérifier que les variables transmises au template sont correctes
        $this->assertSame($employes, $response->getParameter('employes'));
    }
}
