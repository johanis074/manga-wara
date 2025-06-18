<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\User;
use App\Enum\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;

class OrderService
{
        public function __construct(
            private EntityManagerInterface $em,
            private CartService $cartService
        ) {}

        public function createFromStripeSession(Session $session, User $user): Order
    {
        $cart = $this->cartService->getCart();
        $productDetails = [];

        foreach ($cart as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];

            // Incrémentation du champ `sales`
            $product->setSales($product->getSales() + $quantity);
            $this->em->persist($product); // on sauvegarde la modification

            $productDetails[] = [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => $quantity,
                'total' => $product->getPrice() * $quantity,
            ];
        }

        $order = new Order();
        $order->setUser($user);
        $order->setStripeSessionId($session->id);
        $order->setStatus(OrderStatus::Recu);
        $order->setTotal($session->amount_total / 100);
        $order->setProducts($productDetails);

        try {
            $this->em->persist($order);
            $this->em->flush();
        } catch (\Throwable $e) {
            dd('❌ Erreur lors de la création de la commande :', $e->getMessage());
        }

        $this->cartService->clear();

        return $order;
    }
}