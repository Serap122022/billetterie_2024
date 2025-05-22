<?php

namespace App\Tests\Controller;

use App\Controller\EmailController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Mailer\MailerInterface;

class EmailControllerTest extends WebTestCase
{
    public function testSendEmail()
    {
        $client = static::createClient();

        // Créer un mock de MailerInterface
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects($this->once())
            ->method('send');

        // Créer le contrôleur avec le mock de MailerInterface
        $controller = new EmailController($mailer);

        // Appeler la méthode à tester
        $response = $controller->sendEmail($mailer);

        // Vérifier que la réponse est un objet Response
        $this->assertInstanceOf(Response::class, $response);

        // Vérifier le contenu de la réponse
        $this->assertSame('Email envoyé avec succès !', $response->getContent());
    }
}
