<?php
// src/Controller/SearchController.php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Exception;
use Knp\Component\Pager\PaginatorInterface;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function search(Request $request, ProductService $productService): JsonResponse
    {
        try {
            $query = $request->query->get('q', '');
            $products = $productService->findBySearchQuery($query);

            return $this->json($products);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Erreur lors de la recherche : ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/search/results', name: 'app_search_results', methods: ['GET'])]
    public function results(Request $request, ProductService $productService, PaginatorInterface $paginator): Response
    {
        try {
            $query = $request->query->get('q', '');

            $products = $productService->findBySearchQuery($query); // doit retourner une QueryBuilder ou un tableau

            $pagination = $paginator->paginate(
                $products,
                $request->query->getInt('page', 1),
                10
            );

            return $this->render('search/results.html.twig', [
                'query' => $query,
                'pagination' => $pagination,
            ]);
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur affichage des résultats : ' . $e->getMessage()
            ]);
        }
}
}
