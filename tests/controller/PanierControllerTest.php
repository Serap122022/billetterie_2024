<?php

namespace App\Tests\Controller;

use App\Controller\PanierController;
use App\Entity\Billets;
use App\Entity\Panier;
use App\Entity\User;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;

class PanierControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        // Créer des mocks des dépendances
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $panierRepository = $this->createMock(PanierRepository::class);
        $session = $this->createMock(SessionInterface::class);

        // Créer un utilisateur et un billet
        $user = new User();
        $billet = (new Billets())->setTarif(10.00); // Assurez-vous que la méthode setTarif existe

        // Créer un panier avec un billet
        $panier = (new Panier())
            ->setBillet($billet)
            ->setQuantite(2)
            ->setUser($user)
            ->setMontant(20.00) // Montant pour 2 billets à 10.00
        ;

        // Configurer le mock de PanierRepository
        $panierRepository->expects($this->once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn([$panier]); // Retourner un tableau contenant le panier

        // Configurer le mock de la session
        $session->expects($this->once())
            ->method('get')
            ->with('user')
            ->willReturn($user);

        // Créer le contrôleur avec les mocks
        $controller = new PanierController($entityManager, $panierRepository);

        // Appeler la méthode à tester
        $response = $controller->index(new Request(), $session);

        // Vérifier que la réponse est un objet Response
        $this->assertInstanceOf(Response::class, $response);

        // Vérifier que le template correct est rendu
        $this->assertSame('panier/index.html.twig', $response->getTargetTemplate());

        // Vérifier que les variables transmises au template sont correctes
        $this->assertArrayHasKey('panierItems', $response->getParameters());
        $this->assertArrayHasKey('nombreBillets', $response->getParameters());
        $this->assertArrayHasKey('totalMontant', $response->getParameters());
        $this->assertArrayHasKey('form', $response->getParameters());
    }
}
