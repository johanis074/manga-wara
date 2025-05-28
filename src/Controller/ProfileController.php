<?php
// src/Controller/AccountController.php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use App\Form\ProfileType;
use App\Form\EmailType;
use App\Form\PasswordType;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as HasherUserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_profile')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        HasherUserPasswordHasherInterface $hasher,
        PaginatorInterface $paginator
    ): Response {
        $user = $this->getUser();

        // === Formulaire profil ===
        $profileForm = $this->createForm(ProfileType::class, $user);
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            // Gestion de l'image
            $picture = $profileForm->get('pictureUser')->getData();
            if ($picture) {
                $fileName = uniqid().'.'.$picture->guessExtension();
                $picture->move($this->getParameter('user_pictures_directory'), $fileName);
                $user->setPictureUser($fileName);
            }
            $em->flush();
            $this->addFlash('success', 'Profil mis à jour.');
            return $this->redirectToRoute('app_account');
        }

        // === Formulaire email ===
        $emailForm = $this->createForm(EmailType::class, $user);
        $emailForm->handleRequest($request);

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Email modifié.');
            return $this->redirectToRoute('app_account');
        }

        // === Formulaire mot de passe ===
        $passwordForm = $this->createForm(PasswordType::class);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $current = $passwordForm->get('current_password')->getData();
            $new = $passwordForm->get('new_password')->getData();

            if (!$hasher->isPasswordValid($user, $current)) {
                $this->addFlash('error', 'Mot de passe actuel incorrect.');
            } else {
                $user->setPassword($hasher->hashPassword($user, $new));
                $em->flush();
                $this->addFlash('success', 'Mot de passe mis à jour.');
                return $this->redirectToRoute('app_account');
            }
        }

        // === Formulaire adresses ===
        $addressForm = $this->createForm(AddressType::class);
        $addressForm->handleRequest($request);

        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            if (!$addressForm->get('deliveryDifferent')->getData()) {
                $user->setDeliveryAddress(null); // vide si non cochée
            }
            $em->flush();
            $this->addFlash('success', 'Adresses mises à jour.');
            return $this->redirectToRoute('app_account');
        }
        // === Récupération des commandes (3 derniers mois) ===
        $threeMonthsAgo = new \DateTimeImmutable('-3 months');

        $query = $em->getRepository(Order::class)->createQueryBuilder('o')
            ->where('o.user = :user')
            ->andWhere('o.createdAt >= :date')
            ->setParameter('user', $user)
            ->setParameter('date', $threeMonthsAgo)
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery();

        $orders = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('account/dashboard.html.twig', [
            'profileForm' => $profileForm->createView(),
            'emailForm' => $emailForm->createView(),
            'passwordForm' => $passwordForm->createView(),
            'addressForm' => $addressForm->createView(),
            'orders' => $orders,
        ]);
    }
}




