<?php

namespace App\Controller;

use App\Entity\Billets;
use App\Entity\Panier;
use App\Form\PanierType; 
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class PanierController extends AbstractController
{
    private $entityManager;
    private $panierRepository;

    public function __construct(EntityManagerInterface $entityManager, PanierRepository $panierRepository)
    {
        $this->entityManager = $entityManager;
        $this->panierRepository = $panierRepository;
    }

    #[Route('/panier', name: 'panier_index')]
    public function index(Request $request, SessionInterface $session): Response
    {
        $user = $this->getUser();
        $panierItems = [];
        $nombreBillets = 0;
        $totalMontant = 0;

        if (!$session->has('panier')) {
            $session->set('panier', []);
        }

        // Récupére le panier de la session
        $panier = $session->get('panier');

        // dump($panier);

        // Logique pour un utilisateur connecté
        if ($user) {
            $panierItems = $this->entityManager->getRepository(Panier::class)->findBy(['user' => $user]);

            foreach ($panierItems as $item) {
                foreach ($item->getBillet() as $billet) {
                    $totalMontant += $billet->getTarif() * $item->getQuantite();
                }
                $nombreBillets += $item->getQuantite();
            }
        } else {
            // Gestion du panier pour les utilisateurs non authentifiés
            foreach ($panier as $id => $item) {
                if (!is_numeric($id) || intval($id) <= 0) {
                    continue; 
                }

                $billet = $this->entityManager->getRepository(Billets::class)->find($id);

                if (!$billet) {
                    unset($panier[$id]); // Supprime les billets invalides
                    continue;
                }

                if (isset($item['quantity'])) {
                    $panierItems[] = [
                        'billet' => $billet,
                        'quantite' => $item['quantity']
                    ];
                    $nombreBillets += $item['quantity'];
                    $totalMontant += $billet->getTarif() * $item['quantity'];
                }
            }
        }

        // Création et gestion du formulaire
        $form = null;
        if ($user) {
            $form = $this->createForm(PanierType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if ($nombreBillets <= 0) {
                    $this->addFlash('error', 'Votre panier est vide.');
                    return $this->redirectToRoute('panier_index');
                }

                // Mise à jour de la session
                $session->set('panierItems', $panierItems);
                $session->set('totalMontant', $totalMontant);

                return $this->redirectToRoute('app_order');
            }
        }

        return $this->render('panier/index.html.twig', [
            'panierItems' => $panierItems,
            'nombreBillets' => $nombreBillets,
            'totalMontant' => $totalMontant,
            'form' => $form ? $form->createView() : null,
        ]);
    }


#[Route('/{id}/add', name: 'panier_add', methods: ['POST'])]
public function add($id, Request $request, SessionInterface $session, EntityManagerInterface $em): Response
{
    // Récupération du panier depuis la session
    $panier = $session->get('panier', []);
    $quantity = (int) $request->request->get('quantity', 1);

    // Récupère l'objet Billet correspondant à l'ID
    $billet = $em->getRepository(Billets::class)->find($id);
    
    if ($billet) {
        // Vérifie si le billet avec cet ID est déjà dans le panier
        if (isset($panier[$id])) {
            // Si le billet est déjà dans le panier, ajoute la quantité existante avec la nouvelle quantité
            $panier[$id]['quantity'] += $quantity;
        } else {
            // Sinon, on ajoute ce billet avec la quantité initiale
            $panier[$id] = [
                'quantity' => $quantity,
                'tarif' => $billet->getTarif(), // Ajout du tarif du billet pour pouvoir calculer le montant
                'id' => $id
            ];
        }

        // Mise à jour de la session panier
        $session->set('panier', $panier);

        // Ajout dans la base de données pour l'utilisateur connecté
        $user = $this->getUser (); // Récupérer l'utilisateur connecté
        if ($user) {
            // Vérifie si le billet est déjà dans le panier de l'utilisateur
            $panierRepo = $em->getRepository(Panier::class);
            $existingPanier = $panierRepo->findOneBy(['user' => $user, 'billet' => $billet]);

            if ($existingPanier) {
                // Incrémente la quantité
                $newQuantity = $existingPanier->getQuantite() + $quantity;
                $existingPanier->setQuantite($newQuantity);
                $existingPanier->setMontant($billet->getTarif() * $newQuantity); // Met à jour le montant
                $em->persist($existingPanier);
            } else {
                // Ajoute un nouveau billet dans le panier
                $panierEntity = new Panier();
                $panierEntity->setUser ($user);
                $panierEntity->setBillet($billet);
                $panierEntity->setQuantite($quantity);
                $panierEntity->setMontant($billet->getTarif() * $quantity); // Calcul du montant
                $em->persist($panierEntity);
            }

            // Enregistre les modifications dans la base de données
            $em->flush();
        }
    }

    // Redirige l'utilisateur vers la page du panier
    return $this->redirectToRoute('panier_index');
}


    #[Route('/{id}/remove', name: 'panier_remove')]
    public function remove($id, SessionInterface $session): RedirectResponse
    {
        $panier = $session->get('panier', []);
        if (isset($panier[$id])) {
            if ($panier[$id]['quantity'] > 1) {
                // Réduire la quantité de 1
                $panier[$id]['quantity'] -= 1;
            } else {
                unset($panier[$id]);
            }
        }
    
        // Mettre à jour le panier dans la session
        $session->set('panier', $panier);
    
        // Gestion du panier pour les utilisateurs connectés
        $user = $this->getUser ();
        if ($user) {
            $panierEntity = $this->panierRepository->findOneBy([
                'user' => $user,
                'billet' => $id
            ]);
    
            if ($panierEntity) {
                // Réduire la quantité dans la base de données
                if ($panierEntity->getQuantite() > 1) {
                    $panierEntity->setQuantite($panierEntity->getQuantite() - 1);
                } else {
                    // Si la quantité est 1, supprimer l'élément
                    $this->entityManager->remove($panierEntity);
                }
                $this->entityManager->flush();
            }
        }
    
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/{id}/delete', name: 'panier_delete')]
    public function delete($id, SessionInterface $session): RedirectResponse
    {
        $panier = $session->get('panier', []);
        if (isset($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        $user = $this->getUser();
        if ($user) {
            $panierEntity = $this->panierRepository->findOneBy([
                'user' => $user,
                'billet' => $id
            ]);

            if ($panierEntity) {
                $this->entityManager->remove($panierEntity);
                $this->entityManager->flush();
            }
        }

        return $this->redirectToRoute('panier_index');
    }

    #[Route('/clear', name: 'panier_clear')]
    public function clear(SessionInterface $session, EntityManagerInterface $entityManager): RedirectResponse
    {
        $session->set('panier', []);

        $user = $this->getUser();
        if ($user) {
            $panierItems = $this->panierRepository->findBy(['user' => $user]);
            foreach ($panierItems as $item) {
                $this->entityManager->remove($item);
            }
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('panier_index');
    }

    
    #[Route('/acces', name: 'panier_acces')]
public function passerAuPaiement(): Response
{
    return $this->render('panier/acces.html.twig');
}
}
