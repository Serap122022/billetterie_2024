<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailerService
{
    private $mailer;
    private $router;

    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public function sendEmailWithLink(string $to, string $subject, string $routeName, array $routeParams = [])
    {
        // Générer l'URL absolue du lien
        $link = $this->router->generate($routeName, $routeParams, UrlGeneratorInterface::ABSOLUTE_URL);

        // ConstruiT le message
        $email = (new Email())
            ->from('jo.2024.mail@gmail.com')
            ->to($user->getEmail())
            ->subject($subject)
            ->html("
                <p>Bonjour,</p>
                <p>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
                <p><a href='$link'>Cliquez ici</a></p>
                <p>Cordialement.</p>
            ");

        // Envoit l'e-mail
        $this->mailer->send($email);
    }
}
