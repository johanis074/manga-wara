<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Exception;

final class GoodDealController extends AbstractController
{
    #[Route('/goodDeals', name: 'product_good_deals')]
    public function bonPlans(
        Request $request,
        ProductService $productService,
        PaginatorInterface $paginator
    ): Response {
        try {
            $query = $productService->findBonPlanProducts(); // doit retourner un QueryBuilder ou tableau

            $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                10
            );

            return $this->render('good_deal/index.html.twig', [
                'pagination' => $pagination,
            ]);
        } catch (Exception $e) {
            // Log si nécessaire
            $this->addFlash('danger', 'Une erreur est survenue lors du chargement des bons plans.');

            return $this->render('error/500.html.twig', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}


