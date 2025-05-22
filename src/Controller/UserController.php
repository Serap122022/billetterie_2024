<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/user', name: 'app_user')]
    public function index(ValidatorInterface $validator): Response
    {
        // Stocke les erreurs de validation de chaque utilisateur
        $validationErrors = [];

        // Récupére tous les utilisateurs
        $users = $this->entityManager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                $validationErrors[$user->getId()] = $errors;
            }
        }

        return $this->render('_admin/gestion_utilisateurs/index.html.twig', [
            'controller_name' => 'User Controller',
            'users' => $users,
            'validationErrors' => $validationErrors, 
        ]);
    }

    #[Route('/user/toggle/{id}', name: 'app_user_toggle')]
    public function toggleUser (int $id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if ($user) {
            // Inverse l'état actif
            $user->setActive(!$user->isActive());
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_user');
    }

    #[Route('/user/delete/{id}', name: 'app_user_delete')]
    public function deleteUser (int $id): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if ($user) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_user');
    }
}