<?php

namespace App\Service;

use App\Entity\User;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeService
{
    private string $stripeSecretKey;

    public function __construct(string $stripeSecretKey)
    {
        $this->stripeSecretKey = $stripeSecretKey;
        Stripe::setApiKey($this->stripeSecretKey);
    }

    public function createCartCheckoutSession(array $cartItems, string $successUrl, string $cancelUrl, User $user): Session
    {
        $lineItems = [];

        foreach ($cartItems as $item) {
            $product = $item['product'];
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $product->getName()],
                    'unit_amount' => $product->getPrice() * 100,
                ],
                'quantity' => $item['quantity'],
            ];
        }

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $successUrl, // PAS de session_id ici
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'user_id' => $user->getId(),
            ],
        ]);
    }
}
