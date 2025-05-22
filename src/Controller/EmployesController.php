<?php

namespace App\Controller;

use App\Entity\Employes;
use App\Form\EmployesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[Route('/employes')]
#[IsGranted('ROLE_EMPLOYE', message: 'Access Denied')]
final class EmployesController extends AbstractController
{
    #[Route(name: 'app_employes', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $employes = $entityManager->getRepository(Employes::class)->findAll();
    
        // Ajoute les 8 premiers caractères du mot de passe haché à chaque employé
        foreach ($employes as $employe) {
            $employe->partialPasswordHash = substr($employe->getPasswordHash(), 0, 8); 
        }
    
        return $this->render('_employes/gestion_scan/index.html.twig', [
            'employes' => $employes,
        ]);
    }

}