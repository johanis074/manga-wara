<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    #[Route('/cgu', name: 'cgu')]
    public function termsOfUse(): Response
    {
        return $this->render('legal/cgu.html.twig');
    }

    #[Route('/cgv', name: 'cgv')]
    public function termsOfSale(): Response
    {
        return $this->render('legal/cgv.html.twig');
    }

    #[Route('/mentions-legales', name: 'mentions_legales')]
    public function legalNotice(): Response
    {
        return $this->render('legal/mentions_legales.html.twig');
    }

    #[Route('/politique-confidentialite', name: 'politique_confidentialite')]
    public function privacyPolicy(): Response
    {
        return $this->render('legal/politique_confidentialite.html.twig');
    }
}
