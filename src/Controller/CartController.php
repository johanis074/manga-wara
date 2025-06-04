<?php

namespace App\Controller;

use FPDF;
use App\Entity\Book;
use App\Entity\Figurine;
use Symfony\Service\cart;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\StripeService;
use App\Repository\ProductRepository;
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

            return $this->render('cart/index.html.twig', [
                'cart' => $cartService->getCart(),
                'total' => $cartService->getTotal(),
                'totalItems' => array_sum(array_column($cartService->getCart(), 'quantity')),
                'stripe_public_key' => $_ENV['STRIPE_PUBLIC_KEY']
            ]);
        }
        #[Route('/cart/add/{type}/{id}', name: 'cart_add', methods: ['POST'])]
            public function add(string $type, int $id, CartService $cartService, ProductRepository $productRepository): JsonResponse
            {
                // Affiche les valeurs pour déboguer
                dump($type, $id); 

                // Recherche du produit en fonction du type et de l'ID
                $product = $productRepository->findByTypeAndId($type, $id);
                if (!$product) {
                    return new JsonResponse(['success' => false, 'message' => 'Produit introuvable'], 404);
                }

                // Ajoute le produit au panier
                $cartService->add($id, $type);

                // Retourne la réponse
                return new JsonResponse([
                    'success' => true,
                    'totalItems' => array_sum(array_column($cartService->getCart(), 'quantity')),
                    'totalPrice' => $cartService->getTotal(),
                ]);
            }

        #[Route('/cart/remove/{type}/{id}', name: 'cart_remove', methods: ['POST'])]
        public function remove(string $type, int $id, CartService $cartService, ProductRepository $productRepository): Response
        {
            if (!$productRepository->findByTypeAndId($type, $id)) {
                return $this->redirectToRoute('cart_index');
            }

            $cartService->remove($id, $type);

            return $this->redirectToRoute('cart_index');
        }

        #[Route('/cart/checkout', name: 'cart_checkout', methods: ['POST'])]
public function checkout(CartService $cartService, StripeService $stripe): JsonResponse
{
    $cartItems = $cartService->getCart();
    if (!$this->getUser()) {
        return new JsonResponse(['error' => 'Non autorisé'], Response::HTTP_UNAUTHORIZED);
    }
    if (empty($cartItems)) {
        return new JsonResponse(['error' => 'Panier vide'], 400);
    }

    $session = $stripe->createCartCheckoutSession(
        $cartItems,
        $this->generateUrl('payment_success', ['session_id' => '{CHECKOUT_SESSION_ID}'], 0),
        $this->generateUrl('payment_cancel', [], 0),
        $this->getUser()
    );

    return new JsonResponse(['id' => $session->id]);
}

    #[Route('/paiement/success', name: 'payment_success')]
    public function paymentSuccess(Request $request, CartService $cartService, OrderService $orderService, EntityManagerInterface $em
    ): Response {
        $sessionId = $request->query->get('session_id');
        if (!$sessionId) {
            return new Response('ID session manquant', 400);
        }

        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
        } catch (\Exception $e) {
            return new Response('Session Stripe invalide', 400);
        }

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
    }
}

