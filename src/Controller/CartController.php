<?php

namespace App\Controller;

use App\Entity\Book;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $cartService;
    private $entityManager;

    public function __construct(CartService $cartService, EntityManagerInterface $entityManager)
    {
        $this->cartService = $cartService;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add", methods: ["POST"])
     */
    public function add(int $id): Response
    {
        $book = $this->entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            $this->addFlash('error', 'Le livre n\'existe pas.');
            return $this->redirectToRoute('cart_view');
        }

        $this->cartService->add($book->getId());
        $this->addFlash('success', 'Le livre a été ajouté au panier.');

        return $this->redirectToRoute('cart_view');
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove", methods: ["POST"])
     */
    public function remove(int $id): Response
    {
        $this->cartService->remove($id);
        $this->addFlash('success', 'Le livre a été retiré du panier.');

        return $this->redirectToRoute('cart_view');
    }

    /**
     * @Route("/cart", name="cart_view")
     */
    public function view(): Response
    {
        $cartItems = $this->cartService->get();
        $books = [];

        if (!empty($cartItems)) {
            $bookIds = array_keys($cartItems);
            $books = $this->entityManager->getRepository(Book::class)->findBy(['id' => $bookIds]);
        }

        $cartWithBooks = [];
        foreach ($cartItems as $bookId => $item) {
            $book = $books[array_search($bookId, array_column($books, 'id'))] ?? null;
            if ($book) {
                $cartWithBooks[] = [
                    'book' => $book,
                    'quantity' => $item['quantity'],
                ];
            }
        }

        $total = $this->cartService->getTotal($books);

        return $this->render('cart/index.html.twig', [
            'cart' => $cartWithBooks,
            'total' => $total,
        ]);
    }
}