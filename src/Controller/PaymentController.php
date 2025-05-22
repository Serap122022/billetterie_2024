<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrderItems;
use App\Entity\Payment;
use App\Form\PaymentMockType;
use App\Enum\PaymentStatusEnum;
use App\Repository\OrderItemsRepository;
use App\Repository\PanierRepository;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PaymentController extends AbstractController
{
    private $entityManager;
    private $panierRepository;

    public function __construct(EntityManagerInterface $entityManager, PanierRepository $panierRepository)
    {
        $this->entityManager = $entityManager;
        $this->panierRepository = $panierRepository;
    }

    #[Route('/payment', name: 'payment')]
    public function payment(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $montantTotal = $request->getSession()->get('totalPrice', 0);
        $totalBillets = $request->getSession()->get('totalBillets', 0);

        return $this->render('payment/index.html.twig', [
            'user' => $user,
            'totalBillets' => $totalBillets,
            'montantTotal' => $montantTotal,
        ]);
    }

    #[Route('/payment/mock', name: 'payment_mock')]
    public function showPaymentForm(Request $request, Security $security): Response
    {
        $user = $security->getUser();
        $montantTotal = $request->getSession()->get('totalPrice');

        $form = $this->createForm(PaymentMockType::class, null, [
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                return $this->redirectToRoute('payment_process');
            } else {
                $errors = [];
                foreach ($form->getErrors(true) as $error) {
                    $errors[$error->getOrigin()->getName()] = $error->getMessage();
                }
                $this->addFlash('error', 'Veuillez corriger les erreurs dans le formulaire.');
            }
        }

        return $this->render('payment/payment.html.twig', [
            'form' => $form->createView(),
            'montantTotal' => $montantTotal,
            'email' => $user->getEmail(),
            'errors' => $errors ?? [],
        ]);
    }

    #[Route('/payment/process', name: 'payment_process')]
    public function processPayment(Request $request, Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour effectuer un paiement.');
        }

        $orderId = $request->getSession()->get('orderId');
        if (!$orderId) {
            throw new \InvalidArgumentException('Aucune commande associée à ce paiement.');
        }

        $order = $this->entityManager->getRepository(Orders::class)->find($orderId);
        if (!$order || $order->getUser() !== $user) {
            throw $this->createAccessDeniedException("Cette commande ne vous appartient pas ou est introuvable.");
        }

        if ($order->isPaid()) {
            throw new \LogicException("Cette commande a déjà été payée.");
        }

        $montantTotal = $request->getSession()->get('totalPrice', 0);
        if ($montantTotal <= 0) {
            throw new \InvalidArgumentException('Le montant total doit être un nombre positif.');
        }

        // Simulation de paiement réussi
        $order->setPaid(true);
        $this->entityManager->flush();

        $payment = new Payment();
        $payment->setMontant($montantTotal);
        $payment->setStatutPaiement(PaymentStatusEnum::COMPLETED);
        $payment->setMethodePaiement('Mock'); 
        $payment->setUtilisateur($user);

        $orderItems = $order->getOrderItems();
        foreach ($orderItems as $orderItem) {
            $billet = $orderItem->getBillet();
            if ($billet) {
                $payment->setBillet($billet);
                break;
            }
        }

        if (!$payment->getBillet()) {
            throw new \RuntimeException('Aucun billet associé à cette commande.');
        }

        $this->entityManager->persist($payment);
        $this->entityManager->flush();

        // Suppression du panier utilisateur
        $panierItems = $this->panierRepository->findBy(['user' => $user]);
        foreach ($panierItems as $item) {
            $this->entityManager->remove($item);
        }
        $this->entityManager->flush();

        // Nettoyage session
        $request->getSession()->remove('panier');
        $request->getSession()->remove('totalBillets');
        $request->getSession()->remove('totalPrice');

        // Redirection vers la page de succès avec l'ID de commande
        // return $this->redirectToRoute('payment_success_id', ['id' => $order->getId()]);
        return $this->redirectToRoute('payment_success');
    }

    #[Route('/payment/success/{id}', name: 'payment_success_id')]
    public function paymentSuccessId(int $id, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $order = $entityManager->getRepository(Orders::class)->find($id);
        if (!$order) {
            throw $this->createNotFoundException('Commande non trouvée.');
        }

        $orderItems = $order->getOrderItems();
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $uniqueTicketKey = null;
        foreach ($orderItems as $item) {
            if ($item->getUniqueTicketKey()) {
                $uniqueTicketKey = $item->getUniqueTicketKey();
                break;
            }
        }

        if (!$uniqueTicketKey) {
            throw $this->createNotFoundException('Clé unique du ticket introuvable.');
        }

        $orderKey = $order->getOrderKey();

        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $uniqueTicketKey,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            labelText: 'Billet sécurisé',
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );

        $result = $builder->build();

        $qrCodePath = $this->getParameter('kernel.project_dir') . '/public/assets/qr_codes/' . $orderKey . '.png';
        file_put_contents($qrCodePath, $result->getString());

        $qrCodeUrl = '/assets/qr_codes/' . $orderKey . '.png';
        $ordersHistory = $entityManager->getRepository(Orders::class)->findBy(['user' => $user]);
        $totalPrice = $order->getTotalPrice();

        $session->set('hasPaid', true);
        $hasPaid = $session->get('hasPaid', false);

        return $this->render('payment/qr_code.html.twig', [
            'qrCodePath' => $qrCodeUrl,
            'orderItems' => $orderItems,
            'ordersHistory' => $ordersHistory,
            'order' => $order,
            'totalPrice' => $totalPrice,
            'orderKey' => $orderKey,
            'hasPaid' => $hasPaid,
        ]);
    }

    #[Route('/validate/{uniqueTicketKey}', name: 'ticket_validate')]
    public function validateTicket(string $uniqueTicketKey, OrderItemsRepository $orderItemsRepository, EntityManagerInterface $entityManager): Response
    {
        $orderItem = $orderItemsRepository->findOneBy(['uniqueTicketKey' => $uniqueTicketKey]);

        if (!$orderItem) {
            return $this->json([
                'status' => 'error',
                'message' => 'Ticket introuvable ou invalide. Veuillez vérifier la clé du ticket.'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'status' => 'success',
            'message' => 'Ticket valide.',
            'orderItemId' => $orderItem->getId(),
        ]);
    }

    #[Route('/payment/success', name: 'payment_success')]
    public function paymentSuccess(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        // Récupère l'ID de la commande depuis la session
        $orderId = $request->getSession()->get('orderId');
        
        if (!$orderId) {
            throw $this->createNotFoundException('Aucune commande trouvée en session.');
        }
    
        // Marque l'utilisateur comme ayant payé en session
        $session->set('hasPaid', true);
        
        return $this->render('payment/success.html.twig', [
            'orderId' => $orderId,
        ]);
    }

    #[Route('/payment/cancel', name: 'payment_cancel')]
    public function paymentCancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }
    
    #[Route('/payment/history', name: 'payment_history')]
    public function paymentHistory(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }
    
        // Récupère les commandes de l'utilisateur
        $orders = $entityManager->getRepository(Orders::class)->findBy(['user' => $user]);
    
        // Calcule le montant total payé
        $totalPrice = 0;
        foreach ($orders as $order) {
            $totalPrice += $order->getTotalPrice(); 
        }
    
        return $this->render('payment/history.html.twig', [
            'orders' => $orders,
            'totalPrice' => $totalPrice, // Passe le montant total au template
        ]);
    }

          #[Route('/payment/conditions', name: 'payment_conditions')]
    public function conditions(): Response
    {
        return $this->render('payment/legal.html.twig');
    }

     #[Route('/payment/confidentialite', name: 'payment_confidentialite')]
    public function confidentialite(): Response
    {
        return $this->render('payment/confidentialite.html.twig');
    }
}
