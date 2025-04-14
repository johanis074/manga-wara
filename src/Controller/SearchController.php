<?php
// src/Controller/SearchController.php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function search(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $query = $request->query->get('q', '');
        $products = $productRepository->findBySearchQuery($query);

        return $this->json($products);
    }

    #[Route('/search/results', name: 'app_search_results', methods: ['GET'])]
    public function results(Request $request, ProductRepository $productRepository): Response
    {
        $query = $request->query->get('q', '');
        $products = $productRepository->findBySearchQuery($query);

        return $this->render('search/results.html.twig', [
            'query' => $query,
            'products' => $products,
        ]);
    }
}


