<?php

namespace App\Controller;

use FPDF;
use App\Entity\Book;
use App\Entity\Product;
use App\Entity\Figurine;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
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

    return new JsonResponse([
        'success' => true,
        'totalItems' => array_sum(array_column($cartService->getCart(), 'quantity')), // 🔥 Corrige le calcul du total
        'totalPrice' => $cartService->getTotal()
    ]);
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


#[Route('/cart/pdf', name: 'cart_pdf')]
public function generatePDF(SessionInterface $session, EntityManagerInterface $entityManager): Response
{
    $cart = $session->get('cart', []);
    $cartWithData = [];
    $total = 0;

    foreach ($cart as $item) {
        $product = null;

        if ($item['type'] === 'book') {
            $product = $entityManager->getRepository(Book::class)->find($item['id']);
        } elseif ($item['type'] === 'figurine') {
            $product = $entityManager->getRepository(Figurine::class)->find($item['id']);
        }

        if ($product) {
            $cartWithData[] = [
                'product' => $product,
                'quantity' => $item['quantity']
            ];
            $total += $product->getPrice() * $item['quantity'];
        }
    }

    // Génération du PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // 🔥 Ajouter le logo en haut à gauche
    $pdf->Image(__DIR__ . '/../../public/uploads/logo.png', 10, 10, 40);


    // 🔥 Ajouter l'adresse en haut à droite
    $pdf->SetXY(130, 15);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 6, utf8_decode("Manga-Wara\n3 square des sports\n75000 Paris, France\nTel: 01 23 45 67 89\nEmail: contact@entreprise.com"), 0, 'R');

    // 🔥 Titre du bon de commande
    $pdf->SetXY(10, 50); // Ajustement sous le logo et l'adresse
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
