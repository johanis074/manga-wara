<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        try {
            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();

            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('login/index.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
            ]);
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur chargement page de connexion : ' . $e->getMessage()
            ]);
        }
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \Exception('Cette méthode est gérée par Symfony via le firewall.');
    }
}

?>
