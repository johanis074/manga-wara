<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\StripeService;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {
        try {
            return $this->render('cart/index.html.twig', [
                'cart' => $cartService->getCart(),
                'total' => $cartService->getTotal(),
                'totalItems' => array_sum(array_column($cartService->getCart(), 'quantity')),
                'stripe_public_key' => $_ENV['STRIPE_PUBLIC_KEY']
            ]);
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur chargement panier : ' . $e->getMessage()
            ]);
        }
    }

    #[Route('/cart/add/{type}/{id}', name: 'cart_add', methods: ['POST'])]
    public function add(string $type, int $id, CartService $cartService, ProductService $productService): JsonResponse
    {
        try {
            $product = $productService->findByTypeAndId($type, $id);
            if (!$product) {
                return new JsonResponse(['success' => false, 'message' => 'Produit introuvable'], 404);
            }

            $cartService->add($id, $type);

            return new JsonResponse([
                'success' => true,
                'totalItems' => array_sum(array_column($cartService->getCart(), 'quantity')),
                'totalPrice' => $cartService->getTotal(),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Erreur ajout panier : ' . $e->getMessage()], 500);
        }
    }

    #[Route('/cart/remove/{type}/{id}', name: 'cart_remove', methods: ['POST'])]
    public function remove(string $type, int $id, CartService $cartService, ProductService $productService): Response
    {
        try {
            if (!$productService->findByTypeAndId($type, $id)) {
                return $this->redirectToRoute('cart_index');
            }

            $cartService->remove($id, $type);
            return $this->redirectToRoute('cart_index');
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur suppression panier : ' . $e->getMessage()
            ]);
        }
    }

    #[Route('/cart/checkout', name: 'cart_checkout', methods: ['POST'])]
    public function checkout(CartService $cartService, StripeService $stripe): JsonResponse
    {
        try {
            $user = $this->getUser();

            if (!$user) {
                return new JsonResponse(['error' => 'Non autorisé'], Response::HTTP_UNAUTHORIZED);
            }

            // Vérification de l’adresse
            if (
                !$user->getFirstName() ||
                !$user->getLastName() ||
                !$user->getAddress() ||
                !$user->getCity() ||
                !$user->getPostalCode()
            ) {
                return new JsonResponse([
                    'redirect' => $this->generateUrl('app_profile'),
                    'message' => 'Veuillez compléter votre adresse avant de passer au paiement.'
                ], 302);
            }

            $cartItems = $cartService->getCart();
            if (empty($cartItems)) {
                return new JsonResponse(['error' => 'Panier vide'], 400);
            }

            $session = $stripe->createCartCheckoutSession(
                $cartItems,
                $this->generateUrl('payment_success', ['session_id' => '{CHECKOUT_SESSION_ID}'], 0),
                $this->generateUrl('payment_cancel', [], 0),
                $user
            );

            return new JsonResponse(['id' => $session->id]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Erreur paiement : ' . $e->getMessage()], 500);
        }
    }

    #[Route('/paiement/success', name: 'payment_success')]
    public function paymentSuccess(Request $request, CartService $cartService, OrderService $orderService, EntityManagerInterface $em): Response
    {
        $sessionId = $request->query->get('session_id');

        if (!$sessionId) {
            return new Response('ID session manquant', 400);
        }

        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            $user = $this->getUser();
            if (!$user) {
                return new Response('Utilisateur non connecté', 401);
            }

            $existingOrder = $em->getRepository(Order::class)->findOneBy([
                'stripeSessionId' => $sessionId,
            ]);

            if (!$existingOrder) {
                $orderService->createFromStripeSession($session, $user);
            }

            return $this->render('checkout/index.html.twig');
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur finalisation commande : ' . $e->getMessage()
            ]);
        }
    }
}
