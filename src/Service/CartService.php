<?php

namespace App\Service;

use App\Entity\Book;

class CartService
{
    private array $cart = [];

    // Ajouter un livre au panier
    public function add(int $bookId): void
    {
        if (isset($this->cart[$bookId])) {
            $this->cart[$bookId]['quantity']++;
        } else {
            $this->cart[$bookId] = ['quantity' => 1];
        }
    }

    // Retirer un livre du panier
    public function remove(int $bookId): void
    {
        unset($this->cart[$bookId]);
    }

    // Obtenir le contenu du panier
    public function get(): array
    {
        return $this->cart;
    }

    // Vider le panier
    public function clear(): void
    {
        $this->cart = [];
    }

    // Calculer le total du panier
    public function getTotal(array $books): float
    {
        $total = 0;

        foreach ($this->cart as $bookId => $item) {
            $book = $books[$bookId] ?? null;
            if ($book) {
                $total += $book->getPrice() * $item['quantity'];
            }
        }

        return $total;
    }
}