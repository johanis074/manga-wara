<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Product;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        try {
            $bookRepository = $entityManager->getRepository(Book::class);
            $books = $bookRepository->findAll();

            $productService = $entityManager->getRepository(ProductService::class);
            $products = $productService->findAll();

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
            $productService = $entityManager->getRepository(ProductService::class);

            $product = $productService->findOneBy([]);
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

    #[Route('/comment/{id}/delete', name: 'comment_delete', methods: ['POST'])]
    public function delete(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        try {
            if ($this->isCsrfTokenValid('delete_comment_' . $comment->getId(), $request->request->get('_token'))) {
                
                // Récupérer l'ID de la figurine ou du livre AVANT suppression
                $figurineId = $comment->getFigurine() ? $comment->getFigurine()->getId() : null;

                // Supprimer le commentaire
                $em->remove($comment);
                $em->flush();

                $this->addFlash('success', 'Commentaire supprimé.');

                if ($figurineId) {
                    return $this->redirectToRoute('figurine_show', ['id' => $figurineId]);
                } else {
                    // Redirection par défaut si pas de figurine liée
                    return $this->redirectToRoute('homepage');
                }
            } else {
                $this->addFlash('error', 'Token CSRF invalide.');
            }
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur suppression commentaire : ' . $e->getMessage()
            ]);
        }

        // Par défaut, si pas redirigé avant
        return $this->redirectToRoute('homepage');
    }
}
