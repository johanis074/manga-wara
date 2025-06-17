<?php

namespace App\Controller;

use App\Service\StripeService;
use App\Service\CartService;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    #[Route('/create-checkout-session', name: 'stripe_checkout', methods: ['POST'])]
    public function checkout(
        StripeService $stripe,
        CartService $cartService,
        Request $request
    ): JsonResponse {
        try {
            $user = $this->getUser();
            if (!$user) {
                return new JsonResponse(['error' => 'Non autorisé'], Response::HTTP_UNAUTHORIZED);
            }

            $cartItems = $cartService->getCart();
            if (empty($cartItems)) {
                return new JsonResponse(['error' => 'Panier vide'], 400);
            }

            $session = $stripe->createCartCheckoutSession(
                $cartItems,
                $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
                $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
                $user
            );

            $request->getSession()->set('stripe_session_id', $session->id);

            return new JsonResponse(['url' => $session->url]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur lors de la création de la session : ' . $e->getMessage()], 500);
        }
    }

    #[Route('/paiement/success', name: 'payment_success')]
    public function success(Request $request, OrderService $orderService, EntityManagerInterface $em): Response
    {
        try {
            $sessionId = $request->getSession()->get('stripe_session_id');
            if (!$sessionId) {
                return new Response('Session Stripe introuvable (non stockée)', 400);
            }

            \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            $user = $this->getUser();
            if (!$user) {
                return new Response('Utilisateur non connecté', 401);
            }

            $order = $em->getRepository(\App\Entity\Order::class)->findOneBy([
                'stripeSessionId' => $sessionId,
            ]);

            if (!$order) {
                $order = $orderService->createFromStripeSession($session, $user);
            }

            $this->addFlash('success', 'Commande enregistrée avec succès.');

            return $this->render('checkout/index.html.twig', [
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur traitement paiement : ' . $e->getMessage()
            ]);
        }
    }

    #[Route('/paiement/cancel', name: 'payment_cancel')]
    public function cancel(): Response
    {
        try {
            return $this->render('payment/cancel.html.twig');
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur affichage page annulation : ' . $e->getMessage()
            ]);
        }
    }
}