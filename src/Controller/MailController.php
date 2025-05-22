<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class MailController extends AbstractController
{
    #[Route('/forgot-password', name: 'forgot_password')]
    public function forgotPassword(
        Request $request, 
        EntityManagerInterface $entityManager, 
        TokenGeneratorInterface $tokenGenerator, 
        MailerInterface $mailer
    ): Response {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            // Recherche l'utilisateur dans User
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            // Si aucun utilisateur trouvé, on cherche dans Admin (ou Employé)
            if (!$user) {
                $user = $entityManager->getRepository(Admin::class)->findOneBy(['email' => $email]);
            }

            if ($user) {
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $user->setResetTokenExpiresAt(new \DateTime('+1 hour')); // Expiration dans 1 heure

                // dump($user); // Vérifie que le token est bien attribué avant l'enregistrement
            
                $entityManager->flush(); // Sauvegarde en base
                // die();
            
                $resetLink = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            
                $emailMessage = (new Email())
                       ->from('jo.2024.mail@gmail.com')
                    // ->from('ad.jo.2024@outlook.com')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation de votre mot de passe')
                    ->html("
                        <p>Bonjour,</p>
                        <p>Vous avez demandé à réinitialiser votre mot de passe.</p>
                        <p><a href='$resetLink'>Cliquez ici pour réinitialiser votre mot de passe</a></p>
                        <p>Ce lien est valide pendant 1 heure.</p>
                    ");
            
                $mailer->send($emailMessage);

                $this->addFlash('success', 'Un e-mail de réinitialisation a été envoyé.');
            } else {
                $this->addFlash('danger', 'Aucun compte trouvé pour cet e-mail.');
            }
                //  Redirige après soumission vers app_login
        return $this->redirectToRoute('app_login');
        }

        return $this->render('mail/forgot_password.html.twig');
    }
  

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(
        string $token,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        FormFactoryInterface $formFactory
    ): Response {
        // Recherche d'un utilisateur avec ce token
        $user = $entityManager->getRepository(User::class)->findOneBy(['resetToken' => $token]);

        if (!$user) {
            $user = $entityManager->getRepository(Admin::class)->findOneBy(['resetToken' => $token]);
        }

        if (!$user) {
            $this->addFlash('danger', 'Ce lien de réinitialisation est invalide.');
            return $this->redirectToRoute('forgot_password');
        }

        // Vérification de l'expiration du token
        if ($user->getResetTokenExpiresAt() < new \DateTime()) {
            $this->addFlash('danger', 'Le lien de réinitialisation a expiré.');
            return $this->redirectToRoute('forgot_password');
        }

        // Création du formulaire
        $form = $formFactory->create(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $password = $data['password'];
            $confirmPassword = $data['confirm_password'];

            // Vérification de la correspondance des mots de passe
            if ($password !== $confirmPassword) {
                $this->addFlash('danger', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            }

            // Hashage du mot de passe et mise à jour
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            // Suppression du token après réinitialisation
            $user->setResetToken(null);
            $user->setResetTokenExpiresAt(null);

            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('mail/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}