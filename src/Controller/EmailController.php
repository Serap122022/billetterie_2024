<?php

namespace App\Controller;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends AbstractController
{
    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('jo.2024.mail@gmail.com')
            ->to('test@mail.com')
            ->subject('Test Mailjet Symfony')
            ->html('<p>Ceci est un test d’email envoyé avec Mailjet et Symfony Mailer.</p>');

        $mailer->send($email);

        return new Response('Email envoyé avec succès !');
    }
}
