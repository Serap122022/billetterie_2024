<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\Billets;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class SecurityController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, SessionInterface $session): Response
    {
        $maxAttempts = 3;  
        $lockoutTime = 300; // 5 minutes en secondes
        
        $attempts = $session->get('login_attempts', 0);
        $lastAttemptTime = $session->get('last_attempt_time', 0);
        $currentTime = time();
    
        $remainingTime = 0;
        if ($attempts >= $maxAttempts && ($currentTime - $lastAttemptTime) < $lockoutTime) {
            $remainingTime = ($lastAttemptTime + $lockoutTime) - $currentTime;
        }
    
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

         // On passe is_login à true
         $form = $this->createForm(UserType::class, null, ['is_login' => true]);
    
        if ($error) {
            $attempts++;
            $session->set('login_attempts', $attempts);
            $session->set('last_attempt_time', $currentTime);
        }
    
        if ($this->getUser()) {
            $session->remove('login_attempts');
            $session->remove('last_attempt_time');
            return $this->redirectToRoute('redirect_after_login');
        }
    
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error ? $error->getMessage() : null,
            'remaining_time' => $remainingTime, // Temps restant en secondes
        ]);
    }

    

    #[Route('/redirect-after-login', name: 'redirect_after_login')]
    public function redirectAfterLogin(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        // Réinitialise le compteur de tentatives
        $session->remove('login_attempts');

        // Vérifie si l'utilisateur est connecté
        $user = $this->getUser ();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
    
        // Récupére le panier de la session
        $sessionPanier = $session->get('panier', []);
    
        // Récupére le panier de l'utilisateur connecté en base de données
        $panierItems = $entityManager->getRepository(Panier::class)->findBy(['user' => $user]);
    
        // Crée un tableau associatif des billets déjà présents dans le panier en base
        $existingPanierItems = [];
        foreach ($panierItems as $panierItem) {
            $existingPanierItems[$panierItem->getBillet()->getId()] = $panierItem;
        }
    
        // Fusionne les paniers (session + base de données)
        foreach ($sessionPanier as $id => $item) {
            // Vérifie que l'ID est un entier positif
            if (!is_numeric($id) || intval($id) <= 0) {
                continue; 
            }
    
            $billet = $entityManager->getRepository(Billets::class)->find($id);
            if ($billet) {
                if (isset($existingPanierItems[$billet->getId()])) {
                    // Si l'article existe déjà dans le panier de l'utilisateur, on met à jour la quantité
                    $existingPanierItems[$billet->getId()]->setQuantite($existingPanierItems[$billet->getId()]->getQuantite() + $item['quantity']);
                    // Mettre à jour le montant avec la nouvelle quantité
                    $existingPanierItems[$billet->getId()]->setMontant((float) ($billet->getTarif() * $existingPanierItems[$billet->getId()]->getQuantite()));
                } else {
                    // Sinon, créer un nouvel élément dans le panier de l'utilisateur
                    $panierItem = new Panier();
                    $panierItem->setUser ($user);
                    $panierItem->setBillet($billet);
                    $panierItem->setQuantite($item['quantity']);
                    $panierItem->setMontant((float) ($billet->getTarif() * $item['quantity']));
                    $entityManager->persist($panierItem);
                }
            }
        }
    
        // Sauvegarder les changements en base de données
        $entityManager->flush();
    
        // Ne pas vider le panier de la session
        // $session->remove('panier'); // Cette ligne est commentée pour conserver le panier
    
        // Redirige en fonction des rôles de l'utilisateur
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles, true)) {
            return $this->redirectToRoute('app_admin');
        }
    
        if (in_array('ROLE_EMPLOYE', $roles, true)) {
            return $this->redirectToRoute('scan_qr'); 
        }
        // Redirige vers la page de commande ou panier
        return $this->redirectToRoute('app_order');
    }
    

    #[Route('/access-denied', name: 'app_access_denied')]
    public function accessDenied(): Response
    {
        return $this->render('access_denied/index.html.twig');
    }

    #[Route(path: '/login/check', name: 'app_login_check')]
    public function loginCheck(SessionInterface $session): Response
    {
        // Incrémente le compteur d'échecs
        $attempts = $session->get('login_attempts', 0);
        $session->set('login_attempts', ++$attempts);

        return new Response(); 
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
