<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update the pseudo if provided
            $pseudo = $form->get('pseudo')->getData();
            if ($pseudo) {
                $user->setPseudo($pseudo);  // Correction ici : utilise setPseudo() pour modifier l'attribut
            }

            $entityManager->flush();
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }

    #[Route('/security', name: 'app_security')]
    public function security(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Accès refusé');
        }

        // Formulaire pour modifier l'email
        $emailForm = $this->createFormBuilder($user)
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            ->getForm();

        // Formulaire pour modifier le mot de passe
        $passwordForm = $this->createFormBuilder()
            ->add('current_password', PasswordType::class, [
                'label' => 'Mot de passe actuel'
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmer le nouveau mot de passe'],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
            ])
            ->getForm();

        // Traitement du formulaire email
        $emailForm->handleRequest($request);
        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            $newEmail = $emailForm->get('email')->getData();
            $user->setEmail($newEmail);
            $em->flush();

            $this->addFlash('success', 'Email mis à jour avec succès.');
        }

        // Traitement du formulaire mot de passe
        $passwordForm->handleRequest($request);
        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $currentPassword = $passwordForm->get('current_password')->getData();
            $newPassword = $passwordForm->get('new_password')->getData();

            // Vérification du mot de passe actuel
            if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('error', 'Mot de passe actuel incorrect.');
            } else {
                // Mise à jour du mot de passe
                $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
                $em->flush();

                $this->addFlash('success', 'Mot de passe mis à jour avec succès.');
            }
        }

        return $this->render('profile/index.html.twig', [
            'emailForm' => $emailForm->createView(),
            'passwordForm' => $passwordForm->createView(),
        ]);
    }
}



