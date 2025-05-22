<?php

namespace App\Tests\Controller;

use App\Controller\OrdersController;
use App\Entity\Orders;
use App\Entity\OrdersItem;
use App\Entity\User;
use App\Entity\Billets;
use App\Entity\Panier;
use App\Repository\OrdersRepository;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class OrdersControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();

        // Créer des mocks des dépendances
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $session = $this->createMock(SessionInterface::class);
        $security = $this->createMock(Security::class);
        $ordersRepository = $this->createMock(OrdersRepository::class);
        $panierRepository = $this->createMock(PanierRepository::class);

        // Configurer le mock de la Session et du Security
        $user = new User();
        $session->expects($this->once())
            ->method('get')
            ->with('user')
            ->willReturn($user);
        $session->expects($this->once())
            ->method('get')
            ->with('panier', [])
            ->willReturn([]);
        $session->expects($this->exactly(3))
            ->method('set');
        $security->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        // Créer le contrôleur avec les mocks
        $controller = new OrdersController($entityManager, $security, $ordersRepository, $panierRepository);

        // Appeler la méthode à tester
        $response = $controller->create(new Request(), $session, $entityManager);

        // Vérifier que la réponse est un objet Response
        $this->assertInstanceOf(Response::class, $response);

        // Vérifier que le template correct est rendu
        $this->assertSame('orders/index.html.twig', $response->getTargetTemplate());

        // Vérifier que les variables transmises au template sont correctes
        $this->assertArrayHasKey('form', $response->getParameters());
        $this->assertArrayHasKey('groupedItems', $response->getParameters());
        $this->assertArrayHasKey('totalPrice', $response->getParameters());
        $this->assertArrayHasKey('totalBillets', $response->getParameters());
    }
}


