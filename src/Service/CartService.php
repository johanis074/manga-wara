<?php

namespace App\Service;

use App\Repository\BookRepository;
use App\Repository\FigurineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $session;
    private BookRepository $bookRepository;
    private FigurineRepository $figurineRepository;

    public function __construct(
        RequestStack $requestStack, 
        BookRepository $bookRepository, 
        FigurineRepository $figurineRepository, 
        EntityManagerInterface $entityManager
    ) {
        $this->session = $requestStack->getSession();
        $this->bookRepository = $bookRepository;
        $this->figurineRepository = $figurineRepository;
        $this->entityManager = $entityManager;
    }

    // ✅ Ajouter un produit au panier
    public function add(int $id): void
    {
        $cart = $this->session->get('cart', []);

        // Déterminer le type du produit
        if ($this->bookRepository->find($id)) {
            $type = 'book';
        } elseif ($this->figurineRepository->find($id)) {
            $type = 'figurine';
        } else {
            throw new \Exception('Produit introuvable');
        }

        $key = $type . '_' . $id;

        if (!isset($cart[$key])) {
            $cart[$key] = ['id' => $id, 'type' => $type, 'quantity' => 1];
        } else {
            $cart[$key]['quantity']++;
        }

        $this->session->set('cart', $cart);
    }

    // ✅ Supprimer un produit du panier
    public function remove(int $id): void
    {
        $cart = $this->session->get('cart', []);

        // Trouver le type du produit
        $type = null;
        foreach ($cart as $key => $item) {
            if ($item['id'] === $id) {
                $type = $item['type'];
                break;
            }
        }

        if ($type === null) {
            throw new \Exception('Produit introuvable dans le panier');
        }

        $key = $type . '_' . $id;

        if (isset($cart[$key])) {
            unset($cart[$key]);
        }

        $this->session->set('cart', $cart);
    }

    // ✅ Récupérer le panier avec les objets `Book` et `Figurine`
    public function getCart(): array
{
    $cart = $this->session->get('cart', []);
    $cartWithData = [];

    foreach ($cart as $item) {
        $product = null;

        if ($item['type'] === 'book') {
            $product = $this->bookRepository->find($item['id']);
        } elseif ($item['type'] === 'figurine') {
            $product = $this->figurineRepository->find($item['id']);
        }

        if ($product) {
            $cartWithData[] = [
                'product' => $product,
                'quantity' => $item['quantity']
            ];
        }
    }

    return $cartWithData;
}

    // ✅ Calculer le total du panier
    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getCart() as $item) {
            if ($item['product']) {
                $total += $item['product']->getPrice() * $item['quantity'];
            }
        }
        return $total;
    }
}

