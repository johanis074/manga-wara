<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class GoodDealController extends AbstractController
{
    #[Route('/goodDeals', name: 'product_good_deals')]
    public function bonPlans(ProductService $productService): Response
    {
        $products = $productService->findBonPlanProducts();

        return $this->render('good_deal/index.html.twig', [
            'products' => $products,
        ]);
    }
}

