<?php
namespace App\Service;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $session;
    private ProductService $productService;

    public function __construct(RequestStack $requestStack, ProductService $productService)
    {
        $this->session = $requestStack->getSession();
        $this->productService = $productService;
    }

    public function add(int $id, string $type): JsonResponse
{
    // Récupère le panier depuis la session
    $cart = $this->session->get('cart', []);

    // Recherche le produit dans le Service
    $product = $this->productService->findByTypeAndId($type, $id);

    // Si le produit n'est pas trouvé, retourner une réponse JSON avec un message d'erreur
    if (!$product) {
        return new JsonResponse(['success' => false, 'message' => 'Produit introuvable'], 404);
    }

    // Créer une clé unique pour le produit dans le panier
    $key = $type . '_' . $id;

    // Si le produit n'est pas encore dans le panier, on l'ajoute avec une quantité de 1
    if (!isset($cart[$key])) {
        $cart[$key] = ['id' => $id, 'type' => $type, 'quantity' => 1];
    } else {
        // Si le produit est déjà dans le panier, on incrémente la quantité
        $cart[$key]['quantity']++;
    }

    // Mettre à jour la session avec le panier modifié
    $this->session->set('cart', $cart);

    // Retourner une réponse JSON avec la réussite et les informations du panier
    return new JsonResponse([
        'success' => true,
        'totalItems' => array_sum(array_column($cart, 'quantity')),  // Calcul du nombre total d'articles
        'totalPrice' => $this->calculateTotal($cart),  // Calcul du total du panier
    ]);
}

// Exemple de méthode pour calculer le total du panier
private function calculateTotal(array $cart): float
{
    $total = 0;
    foreach ($cart as $item) {
        $product = $this->productService->findByTypeAndId($item['type'], $item['id']);
        if ($product) {
            $total += $product->getPrice() * $item['quantity'];
        }
    }
    return $total;
}


    public function getCart(): array
    {
        $cart = $this->session->get('cart', []);
        $cartWithData = [];

        foreach ($cart as $item) {
            $product = $this->productService->findByTypeAndId($item['type'], $item['id']);

            if ($product) {
                $cartWithData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity']
                ];
            }
        }

        return $cartWithData;
    }
    // ✅ Supprimer un produit du panier
    public function remove(int $id, string $type): void
    {
        $cart = $this->session->get('cart', []);

        $key = $type . '_' . $id; // ✅ Utilisation du type pour identifier l'élément

        if (isset($cart[$key])) {
            unset($cart[$key]);
        }

        $this->session->set('cart', $cart);
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

        public function clear(): void
    {
        $this->session->remove('cart');
    }
}
