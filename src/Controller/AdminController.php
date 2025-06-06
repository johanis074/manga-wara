<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Order;
use App\Form\BookType;
use App\Form\UserType;
use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\Figurine;
use App\Enum\OrderStatus;
use App\Form\ProductType;
use App\Form\FigurineType;
use App\Form\RegistrationFormType;
use App\Repository\BookRepository;
use App\Repository\FigurineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class AdminController extends AbstractController
{
        #[Route('/admin', name: 'admin_dashboard')]
    public function adminDashboard(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findBy([], ['id' => 'DESC'], 5);
        $orders = $em->getRepository(Order::class)->findBy([], ['createdAt' => 'DESC'], 10);
        $books = $em->getRepository(Book::class)->findBy([], ['id' => 'DESC'], 5);
        $figurines = $em->getRepository(Figurine::class)->findBy([], ['id' => 'DESC'], 5);


        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'orders' => $orders,
            'books' => $books,
            'figurines' => $figurines,
            'body_class' => 'admin-page'
        ]);
    }


        #[Route('/admin/register', name: 'admin_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $user->setPassword($hashedPassword);
            $user->setPseudo($this->generateRandomPseudo());
            $user->setPictureUser('default.webp');
            $user->setRoles(['ROLE_ADMIN']);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Nouvel administrateur créé avec succès.');

            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/admin_add.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    private function generateRandomPseudo(): string
    {
        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return 'admi' . $randomString;
    }

    #[Route('/admin/users', name: 'admin_users')]
    public function userDashboard(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/user.html.twig', [
            'users' => $users,
            'body_class' => 'admin-page'
        ]);
    }

    #[Route('/admin/user/delete/{id}', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            $this->addFlash('danger', 'Utilisateur introuvable.');
            return $this->redirectToRoute('admin_users');
        }

        if ($this->isCsrfTokenValid('delete_user_' . $user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        } else {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
        }

        return $this->redirectToRoute('admin_users');
    }

    #[Route('/admin/orders', name: 'admin_orders')]
    public function orderDashboard(EntityManagerInterface $em): Response
    {
        $orders = $em->getRepository(Order::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/order.html.twig', [
            'orders' => $orders,
            'body_class' => 'admin-page'
        ]);
    }

    #[Route('/admin/order/status/{id}', name: 'admin_order_next_status', methods: ['POST'])]
    public function nextOrderStatus(int $id, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $order = $em->getRepository(Order::class)->find($id);

        if (!$order) {
            $this->addFlash('danger', 'Commande introuvable.');
            return $this->redirectToRoute('admin_orders');
        }

        if (!$this->isCsrfTokenValid('next_status_' . $order->getId(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('admin_orders');
        }

        $currentStatus = $order->getStatus();
        $nextStatus = OrderStatus::next($currentStatus);

        if ($nextStatus !== null) {
            $order->setStatus($nextStatus);
            $em->flush();
            $this->addFlash('success', 'Statut mis à jour à : ' . $nextStatus->value);
        } else {
            $this->addFlash('info', 'La commande est déjà au statut final.');
        }

        return $this->redirectToRoute('admin_orders');
    }

        #[Route('/admin/utilisateur/{id}', name: 'admin_user_edit')]
    public function editUser(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Utilisateur mis à jour avec succès.');
            return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
        }

        $comments = $user->getComments(); // relation User -> Commentaire

        return $this->render('admin/user_edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'comments' => $comments,
        ]);
    }

        #[Route('/admin/commentaire/{id}/supprimer', name: 'admin_comment_delete', methods: ['POST'])]
    public function deleteComment(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $userId = $comment->getUser()->getId();

        if ($this->isCsrfTokenValid('delete-comment-' . $comment->getId(), $request->request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire supprimé.');
        }

        return $this->redirectToRoute('admin_user_edit', ['id' => $userId]);
    }

        #[Route('/admin/product/{type}/{id}/edit', name: 'product_edit')]
    public function editProduct(
        string $type,
        int $id,
        Request $request,
        EntityManagerInterface $em,
        BookRepository $bookRepository,
        FigurineRepository $figurineRepository
    ): Response {
        $product = $type === 'book'
            ? $bookRepository->find($id)
            : $figurineRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }

        $form = $this->createForm(ProductType::class, $product, [
            'data_class' => get_class($product),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Produit modifié avec succès.');
            $redirectUrl = $request->query->get('redirect') ?? $this->generateUrl('admin_dashboard');
            return $this->redirect($redirectUrl);
        }

        return $this->render('admin/product_edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }


    #[Route('/admin/product/{type}/{id}/delete', name: 'product_delete', methods: ['POST', 'GET'])]
    public function deleteProduct(string $type, int $id, BookRepository $bookRepo, FigurineRepository $figRepo, EntityManagerInterface $em, Request $request): Response
    {
        if ($type === 'book') {
            $book = $bookRepo->find($id);
            if ($book) {
                $em->remove($book);
            }
        } elseif ($type === 'figurine') {
            $figurine = $figRepo->find($id);
            if ($figurine) {
                $em->remove($figurine);
            }
        }

        $em->flush();

        $this->addFlash('success', 'Produit supprimé avec succès.');
            $redirectUrl = $request->query->get('redirect') ?? $this->generateUrl('admin_dashboard');
        return $this->redirect($redirectUrl);
    }



}
