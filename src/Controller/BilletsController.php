<?php

namespace App\Controller;

use App\Entity\Billets;
use App\Form\BilletsType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/billets')]
#[IsGranted('ROLE_ADMIN')] 
class BilletsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        
    }


    #[Route(name: 'billet_index', methods: ['GET'])]
    public function index(): Response
    {
        $billets = $this->entityManager->getRepository(Billets::class)->findAll();
    
        $nombreBillets = count($billets);
    
        return $this->render('_admin/gestion_billets/index.html.twig', [
            'billets' => $billets,
            'nombreBillets' => $nombreBillets, 
        ]);
    }
    
    #[Route('/new', name: 'billet_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $billet = new Billets();
        $form = $this->createForm(BilletsType::class, $billet);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupére les données du formulaire
            $selectedType = $form->get('type')->getData();
            $customType = $form->get('customType')->getData();
    
            // Si "Autres" est sélectionné et un type personnalisé est saisi
            if ($selectedType === 'other' && !empty($customType)) {
                $billet->setType($customType); // Attribue le type personnalisé
            } else {
                $billet->setType($selectedType); // Utilise le type sélectionné
            }
    
            $this->entityManager->persist($billet);
            $this->entityManager->flush();
    
            return $this->redirectToRoute('billet_index');
        }
    
        return $this->render('_admin/gestion_billets/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    

    #[Route('/{id}', name: 'billet_show', methods: ['GET'])]
    public function show(string $id): Response
    {
        $billet = $this->entityManager->getRepository(Billets::class)->find($id);
        
        if (!$billet) {
            throw $this->createNotFoundException('Billet non trouvé');
        }

        return $this->render('_admin/gestion_billets/show.html.twig', [
            'billet' => $billet,
        ]);
    }

    #[Route('/{id}/add', name: 'panier_add', methods: ['POST'])]
public function add($id, Request $request, SessionInterface $session, EntityManagerInterface $em): Response
{
    // Récupération du panier depuis la session
    $panier = $session->get('panier', []);
    $data = json_decode($request->getContent(), true); 

    // Vérifie si $data est null ou si 'quantity' n'est pas défini
    if ($data === null || !isset($data['quantity'])) {
        return new Response('Invalid data', Response::HTTP_BAD_REQUEST);
    }

    $quantity = (int) $data['quantity']; // Récupére la quantité

    // Vérifie si le billet est déjà dans le panier
    if (isset($panier[$id])) {
        $panier[$id]['quantity'] += $quantity; // Ajoute la quantité
    } else {
        $panier[$id] = ['quantity' => $quantity]; // Crée une nouvelle entrée
    }

    // Mise à jour de la session panier
    $session->set('panier', $panier);

    // Ajout dans la base de données pour l'utilisateur connecté
    $user = $this->getUser (); // Récupére l'utilisateur connecté
    if ($user) {
        $panierEntity = new Panier();
        $billet = $em->getRepository(Billets::class)->find($id);
        if ($billet) {
            $panierEntity->setUser ($user);
            $panierEntity->setBillet($billet);
            $panierEntity->setQuantite ($quantity);
            $panierEntity->setMontant($billet->getTarif() * $quantity); // Calcule le montant total

            $em->persist($panierEntity);
            $em->flush();
        }
    }

    // Redirige l'utilisateur vers la page du panier
    return $this->redirectToRoute('panier_index'); 
}

#[Route('/{id}/edit', name: 'billet_edit')]
public function edit(Request $request, Billets $billet): Response
{
    $form = $this->createForm(BilletsType::class, $billet);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $selectedType = $form->get('type')->getData();
        $customType = $form->get('customType')->getData();

        // Si "Autres" est sélectionné et que le champ personnalisé n'est pas vide
        if ($selectedType === 'other' && !empty($customType)) {
            $billet->setType($customType); // Utilise le type personnalisé
        } else {
            $billet->setType($selectedType); // Utilise le type sélectionné
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('billet_index');
    }

    return $this->render('_admin/gestion_billets/edit.html.twig', [
        'form' => $form->createView(),
        'billet' => $billet,
    ]);
    }
    

    #[Route('/{id}/delete', name: 'billet_delete', methods: ['POST'])]
    public function delete(Request $request, Billets $billet, PanierRepository $panierRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $billet->getId(), $request->request->get('_token'))) {
            // Récupére tous les paniers qui contiennent ce billet
            $panierItems = $panierRepository->findBy(['billet' => $billet]);
    
            // Supprime chaque item du panier
            foreach ($panierItems as $item) {
                $this->entityManager->remove($item);
            }
    
            // Supprime le billet
            $this->entityManager->remove($billet);
            $this->entityManager->flush();
        }
    
        return $this->redirectToRoute('billet_index');
    }
}