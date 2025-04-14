<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Figurine;
use App\Repository\ProductRepository;
use App\Service\CartService;
use FPDF;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Service\cart;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index')]
        public function index(CartService $cartService): Response
        {

            return $this->render('cart/index.html.twig', [
                'cart' => $cartService->getCart(),
                'total' => $cartService->getTotal(),
                'totalItems' => array_sum(array_column($cartService->getCart(), 'quantity')),
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


    #[Route('/cart/pdf', name: 'cart_pdf')]
    public function generatePDF(CartService $cartService): Response
    {
        $cartWithData = $cartService->getCart();
        $total = $cartService->getTotal();

        // Génération du PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // 🔥 Logo
        $pdf->Image(__DIR__ . '/../../public/uploads/logo.png', 10, 10, 40);

        // 🔥 Adresse
        $pdf->SetXY(130, 15);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 6, utf8_decode("Manga-Wara\n3 square des sports\n75000 Paris, France\nTel: 01 23 45 67 89\nEmail: contact@entreprise.com"), 0, 'R');

        // 🔥 Titre
        $pdf->SetXY(10, 50);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Bon de Commande', 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Date: ' . date('d/m/Y'), 0, 1);
        $pdf->Ln(5);

        // Table Header
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 10, 'Produit', 1);
        $pdf->Cell(30, 10, 'Quantité', 1);
        $pdf->Cell(40, 10, 'Prix Unitaire (€)', 1);
        $pdf->Cell(40, 10, 'Total (€)', 1);
        $pdf->Ln();

        // Table Body
        $pdf->SetFont('Arial', '', 12);
        foreach ($cartWithData as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];
            $price = $product->getPrice();
            $totalProduct = $price * $quantity;

            $pdf->Cell(80, 10, utf8_decode($product->getName()), 1);
            $pdf->Cell(30, 10, $quantity, 1, 0, 'C');
            $pdf->Cell(40, 10, number_format($price, 2), 1, 0, 'C');
            $pdf->Cell(40, 10, number_format($totalProduct, 2), 1, 0, 'C');
            $pdf->Ln();
        }

        // Total
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(150, 10, 'Total', 1);
        $pdf->Cell(40, 10, number_format($total, 2), 1, 0, 'C');

        // Retourne le PDF
        $response = new Response($pdf->Output('', 'I'));
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }
}

