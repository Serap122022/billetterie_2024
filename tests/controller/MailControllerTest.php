<?php

namespace App\Tests\Controller;

use App\Controller\MailController;
use App\Entity\User;
use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MailControllerTest extends WebTestCase
{
    public function testForgotPassword()
    {
        $client = static::createClient();

        // Créer des mocks des dépendances
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $tokenGenerator = $this->createMock(TokenGeneratorInterface::class);
        $mailer = $this->createMock(MailerInterface::class);

        // Configurer le mock de l'EntityManager pour retourner un utilisateur
        $user = new User();
        $user->setEmail('test@example.com');
        $entityManager->expects($this->exactly(2))
            ->method('getRepository')
            ->willReturnOnConsecutiveCalls(
                $this->createMock(User::class),
                $this->createMock(Admin::class)
            );
        $entityManager->expects($this->once())
            ->method('flush');

        // Créer le contrôleur avec les mocks
        $controller = new MailController($entityManager, $tokenGenerator, $mailer);

        // Appeler la méthode à tester
        $response = $controller->forgotPassword(new Request(), $entityManager, $tokenGenerator, $mailer);

        // Vérifier que la réponse est un objet Response
        $this->assertInstanceOf(Response::class, $response);

        // Vérifier que le template correct est rendu
        $this->assertSame('mail/forgot_password.html.twig', $response->getTargetTemplate());
    }

    public function testResetPassword()
    {
        $client = static::createClient();

        // Créer des mocks des dépendances
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $passwordHasher = $this->createMock(\Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface::class);
        $formFactory = $this->createMock(FormFactoryInterface::class);

        // Configurer le mock de l'EntityManager pour retourner un utilisateur
        $user = new User();
        $user->setEmail('test@example.com');
        $entityManager->expects($this->exactly(2))
            ->method('getRepository')
            ->willReturnOnConsecutiveCalls(
                $this->createMock(User::class),
                $this->createMock(Admin::class)
            );
        $entityManager->expects($this->once())
            ->method('flush');

        // Créer le contrôleur avec les mocks
        $controller = new MailController($entityManager, $passwordHasher, $formFactory);

        // Appeler la méthode à tester
        $response = $controller->resetPassword('token', new Request(), $entityManager, $passwordHasher, $formFactory);

        // Vérifier que la réponse est un objet Response
        $this->assertInstanceOf(Response::class, $response);

        // Vérifier que le template correct est rendu
        $this->assertSame('mail/reset_password.html.twig', $response->getTargetTemplate());
    }
}
