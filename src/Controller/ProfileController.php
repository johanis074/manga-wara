<?php
namespace App\Controller;


use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }

    #[Route('/profile/update-pseudo', name: 'app_profile_update_pseudo', methods: ['POST'])]
    public function updatePseudo(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $newPseudo = $request->request->get('newPseudo');

        if ($newPseudo) {
            $user->$this->setPseudo($newPseudo);
            $entityManager->flush();

            return new Response('Pseudo updated successfully', 200);
        }

        return new Response('Invalid pseudo', 400);
    }
}




