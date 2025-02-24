<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\Figurine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class StripeService
{
    private $session;
    private EntityManagerInterface $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->session = $requestStack->getSession();
        $this->entityManager = $entityManager;
    }

    public function add(int $id, string $type): void
    {
        $cart = $this->session->get('cart', []);

        $key = $type . '_' . $id;

        if (!isset($cart[$key])) {
            $cart[$key] = ['id' => $id, 'type' => $type, 'quantity' => 1];
        } else {
            $cart[$key]['quantity']++;
        }

        $this->session->set('cart', $cart);
    }

    public function remove(int $id, string $type): void
    {
        $cart = $this->session->get('cart', []);
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
            $repository = $item['type'] === 'book'
                ? $this->entityManager->getRepository(Book::class)
                : $this->entityManager->getRepository(Figurine::class);

            $product = $repository->find($item['id']);

            if ($product) {
                $cartWithData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
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

    public function clearCart(): void
    {
        $this->session->remove('cart');
    }

    public function getCartForStripe(): array
    {
        $cart = $this->getCart();
        $lineItems = [];

        foreach ($cart as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['product']->getName(),
                    ],
                    'unit_amount' => $item['product']->getPrice() * 100,
                ],
                'quantity' => $item['quantity'],
            ];
        }

        return $lineItems;
    }
}
