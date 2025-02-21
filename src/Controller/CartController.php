<?php

namespace App\Controller;

use App\Service\CartService;
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
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add', methods: ['POST'])]
    public function add(int $id, CartService $cartService, Request $request): JsonResponse
    {
        $cartService->add($id);
    
        // Vérifie si la requête est AJAX
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'success' => true,
                'totalItems' => count($cartService->getCart()),
                'totalPrice' => $cartService->getTotal()
            ]);
        }
    
        // Redirection classique si la requête n'est pas AJAX
        return $this->redirectToRoute('cart_index');
    }




#[Route('/cart/remove/{id}', name: 'cart_remove')]
public function remove(int $id, CartService $cartService): Response
{
    $cartService->remove($id);
    return $this->redirectToRoute('cart_index');
}

#[Route('/cart/info', name: 'cart_info')]
public function cartInfo(CartService $cartService): JsonResponse
{
    return new JsonResponse([
        'totalItems' => count($cartService->getCart()), // Nombre total d'articles
        'totalPrice' => $cartService->getTotal() // Prix total en €
    ]);
}

}
