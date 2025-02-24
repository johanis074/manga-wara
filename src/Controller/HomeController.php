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
}

