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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('_admin/index.html.twig');
    }

    #[Route('/admin/employes', name: 'app_admin_employes_index')]
    public function employesIndex(EntityManagerInterface $em): Response
    {
        $employes = $em->getRepository(Employes::class)->findAll();

        return $this->render('_admin/gestion_employes/index.html.twig', [
            'employes' => $employes,
        ]);
    }

    
    #[Route('/admin/employes/new', name: 'app_admin_employes_new')]
public function new(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
{
    $employe = new Employes();
    $form = $this->createForm(EmployesType::class, $employe, [
        'edit_mode' => false,
    ]);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        // Hachage du mot de passe
        $plainPassword = $form->get('password')->getData();
        if ($plainPassword) {
            $hashedPassword = $passwordHasher->hashPassword($employe, $plainPassword);
            $employe->setPassword($hashedPassword);
        }

        // Gestion de la logique d'enregistrement
        $em->persist($employe);
        $em->flush();
    
        return $this->redirectToRoute('app_admin_employes_index');
    }
    
    return $this->render('_admin/gestion_employes/new.html.twig', [
        'form' => $form->createView(),
        'edit_mode' => false,
    ]);
}



    #[Route('/admin/employes/{id}/edit', name: 'app_admin_employes_edit')]
    public function edit(Request $request, Employes $employe, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher ): Response
    {
        $form = $this->createForm(EmployesType::class, $employe);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Hachage du mot de passe si un nouveau mot de passe est saisi
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($employe, $plainPassword);
                $employe->setPassword($hashedPassword);
            }
            
            // Les données sont automatiquement mises à jour grâce à l'entité liée
            $em->flush(); // Enregistre les modifications
            $this->addFlash('success', 'Employé modifié avec succès !');
            
            return $this->redirectToRoute('app_admin_employes_index'); // Redirige vers la liste des employés
        }
        
        return $this->render('_admin/gestion_employes/edit.html.twig', [
            'form' => $form->createView(), 
            'employe' => $employe,
        ]);
    }


    #[Route('/admin/employes/{id}/show', name: 'app_admin_employes_show')]
    public function show(Employes $employe): Response
    {
         // Récupère les 8 premiers caractères du hash du mot de passe
    $partialPasswordHash = substr($employe->getPassword(), 0, 8);

        // Ici, on récupère l'employé via la route, Symfony va automatiquement injecter l'objet Employes
        return $this->render('_admin/gestion_employes/show.html.twig', [
            'employe' => $employe,
            'partialPasswordHash' => $partialPasswordHash,
        ]);
    }


    #[Route('/admin/employes/{id}', name: 'app_admin_employes_delete', methods: ['POST'])]
public function delete(Request $request, Employes $employe, EntityManagerInterface $em): Response
{
    // Vérifiez si le jeton CSRF est valide
    if ($this->isCsrfTokenValid('delete' . $employe->getId(), $request->request->get('_token'))) {
        $em->remove($employe);
        $em->flush();
        $this->addFlash('success', 'Employé supprimé avec succès !');
    } else {
        $this->addFlash('error', 'Le jeton CSRF est invalide.');
    }

    return $this->redirectToRoute('app_admin_employes_index');
}

}
