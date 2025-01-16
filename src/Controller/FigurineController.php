<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FigurineController extends AbstractController
{
    #[Route('/figurine', name: 'app_figurine')]
    public function index(): Response
    {
        return $this->render('figurine/index.html.twig', [
            'controller_name' => 'FigurineController',
        ]);
    }
}
