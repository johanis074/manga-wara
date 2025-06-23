<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\FigurineRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepageRedirect(): RedirectResponse
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/home', name: 'app_home')]
    public function index(BookRepository $bookRepository, FigurineRepository $figurineRepository)
    {
        try {
            return $this->render('home/index.html.twig', [
                'newProducts' => array_merge(
                    $bookRepository->findNewBooks(),
                    $figurineRepository->findNewFigurines()
                ),
                'popularProducts' => array_merge(
                    $bookRepository->findPopularBooks(),
                    $figurineRepository->findPopularFigurines()
                ),
                'bestSellers' => array_merge(
                    $bookRepository->findBestSellingBooks(),
                    $figurineRepository->findBestSellingFigurines()
                ),
            ]);
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur chargement accueil : ' . $e->getMessage()
            ]);
        }
    }

    #[Route('/view/{filter}', name: 'app_view')]
    public function viewProducts(string $filter, BookRepository $bookRepository, FigurineRepository $figurineRepository)
    {
        try {
            $products = [];

            switch ($filter) {
                case 'Nouveaux':
                    $products = array_merge(
                        $bookRepository->findNewBooks(6),
                        $figurineRepository->findNewFigurines(6)
                    );
                    break;
                case 'Populaires':
                    $products = array_merge(
                        $bookRepository->findPopularBooks(6),
                        $figurineRepository->findPopularFigurines(6)
                    );
                    break;
                case 'Vendus':
                    $products = array_merge(
                        $bookRepository->findBestSellingBooks(6),
                        $figurineRepository->findBestSellingFigurines(6)
                    );
                    break;
            }

            return $this->render('home/view.html.twig', [
                'products' => $products,
                'filter' => $filter,
            ]);
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur chargement produits : ' . $e->getMessage()
            ]);
        }
    }
}
