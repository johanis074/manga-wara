<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        try {
            $bookRepository = $entityManager->getRepository(Book::class);
            $books = $bookRepository->findAll();

            $productRepository = $entityManager->getRepository(ProductRepository::class);
            $products = $productRepository->findAll();

            foreach ($products as $product) {
                foreach ($books as $book) {
                    $product->addBook($book);
                }
            }

            $entityManager->flush();

            return $this->render('product/index.html.twig', [
                'controller_name' => 'ProductController',
            ]);
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur liaison produits : ' . $e->getMessage()
            ]);
        }
    }

    #[Route('/product/link-books', name: 'app_product_link_books')]
    public function linkBooksToProducts(EntityManagerInterface $entityManager): Response
    {
        try {
            $bookRepository = $entityManager->getRepository(Book::class);
            $productRepository = $entityManager->getRepository(ProductRepository::class);

            $product = $productRepository->findOneBy([]);
            $book = $bookRepository->findOneBy([]);

            if ($product && $book) {
                $product->addBook($book);
                $entityManager->flush();
                return new Response('Livre lié au produit avec succès !');
            }

            return new Response('Aucun livre ou produit trouvé.');
        } catch (\Exception $e) {
            return new Response('Erreur lors de la liaison livre/produit : ' . $e->getMessage(), 500);
        }
    }
}
