<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookRepository;
use App\Repository\FigurineRepository;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(BookRepository $bookRepository, FigurineRepository $figurineRepository)
    {
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
    }



#[Route('/view/{filter}', name: 'app_view')]
public function viewProducts(string $filter, BookRepository $bookRepository, FigurineRepository $figurineRepository)
{
    $products = [];

    switch ($filter) {
        case 'new':
            $products = array_merge(
                $bookRepository->findNewBooks(6),
                $figurineRepository->findNewFigurines(6)
            );
            break;
        case 'popular':
            $products = array_merge(
                $bookRepository->findPopularBooks(6),
                $figurineRepository->findPopularFigurines(6)
            );
            break;
        case 'best':
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
}
}
