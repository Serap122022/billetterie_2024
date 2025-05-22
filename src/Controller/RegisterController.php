<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\FormError;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class RegisterController extends AbstractController
{

#[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
{
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    // Si le formulaire est soumis via AJAX
    if ($request->isXmlHttpRequest()) {
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            try {
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->json(['status' => 'success'], 200);
            } catch (UniqueConstraintViolationException $e) {
                $form->get('email')->addError(new FormError('Cet email est déjà utilisé.'));
            }
        }

        // Collecte des erreurs
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $field = $error->getOrigin()->getName();
            $errors[$field][] = $error->getMessage();
        }

        return $this->json(['status' => 'error', 'errors' => $errors], 400);
    }

    // Pour les requêtes classiques (non-AJAX)
    return $this->render('register/index.html.twig', [
        'form' => $form->createView(),
    ]);
}


#[Route('/terms-conditions', name: 'terms_conditions')]
public function termsConditions(): Response
{
return $this->render('register/terms_conditions.html.twig');
}
}

