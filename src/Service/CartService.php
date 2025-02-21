<?php

namespace App\Service;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\BookRepository;
use App\Repository\FigurineRepository;

class CartService
{
    private $session;
    private BookRepository $bookRepository;
    private FigurineRepository $figurineRepository;

    public function __construct(RequestStack $requestStack, BookRepository $bookRepository, FigurineRepository $figurineRepository)
    {
        $this->session = $requestStack->getSession(); // 🔥 Récupération de la session
        $this->bookRepository = $bookRepository;
        $this->figurineRepository = $figurineRepository;
    }

    public function add(int $id): void
{
    $cart = $this->session->get('cart', []);

    // Vérifier si l'ID correspond à un Book
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


public function remove(int $id): void
{
    $cart = $this->session->get('cart', []);

    // Trouver le type automatiquement
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

    public function getCart(): array
    {
        $cart = $this->session->get('cart', []);
        $cartWithData = [];

        foreach ($cart as $key => $item) {
            if ($item['type'] === 'book') {
                $product = $this->bookRepository->find($item['id']);
            } else {
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

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }
}
