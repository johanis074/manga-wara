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
        try {
            $query = $request->query->get('q', '');
            $products = $productRepository->findBySearchQuery($query);

            return $this->json($products);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Erreur lors de la recherche : ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/search/results', name: 'app_search_results', methods: ['GET'])]
    public function results(Request $request, ProductRepository $productRepository): Response
    {
        try {
            $query = $request->query->get('q', '');
            $products = $productRepository->findBySearchQuery($query);

            return $this->render('search/results.html.twig', [
                'query' => $query,
                'products' => $products,
            ]);
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur affichage des résultats : ' . $e->getMessage()
            ]);
        }
    }
}
