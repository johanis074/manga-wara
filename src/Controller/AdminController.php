<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Figurine;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\Comment;
use App\Enum\OrderStatus;
use App\Form\ProductType;
use App\Form\RegistrationFormType;
use App\Form\UserType;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\BookRepository;
use App\Repository\FigurineRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function adminDashboard(EntityManagerInterface $em): Response
    {
        try {
            $users = $em->getRepository(User::class)->findBy([], ['id' => 'DESC'], 5);
            $orders = $em->getRepository(Order::class)->findBy([], ['createdAt' => 'DESC'], 10);
            $books = $em->getRepository(Book::class)->findBy([], ['id' => 'DESC'], 5);
            $figurines = $em->getRepository(Figurine::class)->findBy([], ['id' => 'DESC'], 5);
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur chargement du tableau de bord : ' . $e->getMessage()
            ]);
        }

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users,
            'orders' => $orders,
            'books' => $books,
            'figurines' => $figurines,
            'body_class' => 'admin-page'
        ]);
    }

    #[Route('/admin/register', name: 'admin_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $hashedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
                $user->setPassword($hashedPassword);
                $user->setPseudo($this->generateRandomPseudo());
                $user->setPictureUser('default.webp');
                $user->setRoles(['ROLE_ADMIN']);

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Nouvel administrateur créé avec succès.');
                return $this->redirectToRoute('admin_dashboard');
            } catch (\Exception $e) {
                return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                    'message' => 'Erreur lors de la création : ' . $e->getMessage()
                ]);
            }
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
        try {
            $users = $em->getRepository(User::class)->findAll();
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur chargement utilisateurs : ' . $e->getMessage()
            ]);
        }

        return $this->render('admin/user.html.twig', [
            'users' => $users,
            'body_class' => 'admin-page'
        ]);
    }

#[Route('/admin/users/delete/{id}', name: 'admin_user_delete', methods: ['POST'])]
public function deleteUser(int $id, Request $request, EntityManagerInterface $em): Response
{
    try {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            $this->addFlash('danger', 'Utilisateur introuvable.');
            return $this->redirectToRoute('admin_users'); // adapte selon ta route
        }

        if ($this->isCsrfTokenValid('delete_user_' . $user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        } else {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
        }
    } catch (\Exception $e) {
        return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
            'message' => 'Erreur lors de la suppression utilisateur : ' . $e->getMessage(),
        ]);
    }

    return $this->redirectToRoute('admin_users'); // adapte selon ta route
}




    #[Route('/admin/orders', name: 'admin_orders')]
    public function orderDashboard(EntityManagerInterface $em): Response
    {
        try {
            $orders = $em->getRepository(Order::class)->findBy([], ['createdAt' => 'DESC']);
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur chargement commandes : ' . $e->getMessage()
            ]);
        }

        return $this->render('admin/order.html.twig', [
            'orders' => $orders,
            'body_class' => 'admin-page'
        ]);
    }

    #[Route('/admin/order/status/{id}', name: 'admin_order_next_status', methods: ['POST'])]
    public function nextOrderStatus(int $id, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        try {
            $order = $em->getRepository(Order::class)->find($id);
            if (!$order) {
                throw new \Exception('Commande introuvable.');
            }

            if (!$this->isCsrfTokenValid('next_status_' . $order->getId(), $request->request->get('_token'))) {
                throw new \Exception('Jeton CSRF invalide.');
            }

            $nextStatus = OrderStatus::next($order->getStatus());
            if ($nextStatus !== null) {
                $order->setStatus($nextStatus);
                $em->flush();
                $this->addFlash('success', 'Statut mis à jour à : ' . $nextStatus->value);
            } else {
                $this->addFlash('info', 'La commande est déjà au statut final.');
            }
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur changement de statut : ' . $e->getMessage()
            ]);
        }

        return $this->redirectToRoute('admin_orders');
    }

    #[Route('/admin/users/{id}', name: 'admin_user_edit')]
    public function editUser(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            try {
                if ($form->isSubmitted() && $form->isValid()) {
                    $em->flush();

                    return $this->json([
                        'status' => 'success',
                        'message' => 'Utilisateur mis à jour avec succès.',
                        'redirect' => $this->generateUrl('admin_user_edit', ['id' => $user->getId()])
                    ]);
                }

                if ($form->isSubmitted() && !$form->isValid()) {
                    $errors = [];
                    foreach ($form->getErrors(true) as $error) {
                        $field = $error->getOrigin()->getName();
                        $errors[$field][] = $error->getMessage();
                    }

                    return $this->json([
                        'status' => 'error',
                        'message' => 'Le formulaire contient des erreurs.',
                        'errors' => $errors,
                    ], 400);
                }
            } catch (\Exception $e) {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Erreur mise à jour utilisateur : ' . $e->getMessage()
                ], 500);
            }
        }

        // Requête normale (non-AJAX)
        return $this->render('admin/user_edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'comments' => $user->getComments(),
        ]);
    }


    #[Route('/admin/commentaire/{id}/supprimer', name: 'admin_comment_delete', methods: ['POST'])]
    public function deleteComment(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $userId = $comment->getUser()->getId();

        try {
            if ($this->isCsrfTokenValid('delete-comment-' . $comment->getId(), $request->request->get('_token'))) {
                $em->remove($comment);
                $em->flush();
                $this->addFlash('success', 'Commentaire supprimé.');
            }
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur suppression commentaire : ' . $e->getMessage()
            ]);
        }

        return $this->redirectToRoute('admin_user_edit', ['id' => $userId]);
    }

    #[Route('/admin/product/{type}/{id}/edit', name: 'product_edit')]
public function editProduct(string $type, int $id, Request $request, EntityManagerInterface $em, BookRepository $bookRepo, FigurineRepository $figRepo, SluggerInterface $slugger ): Response {
    $product = $type === 'book' ? $bookRepo->find($id) : $figRepo->find($id);

    if (!$product) {
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig', [
            'message' => 'Produit non trouvé.'
        ]);
    }

    $form = $this->createForm(ProductType::class, $product, ['data_class' => get_class($product)]);
    $form->handleRequest($request);

    try {
        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData();

            if ($pictureFile instanceof UploadedFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.webp';

                try {
                    $this->convertImageToWebpAndSave($pictureFile, $newFilename);
                    $product->setPicture($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur image : ' . $e->getMessage());
                }
            }

            $em->flush();
            $this->addFlash('success', 'Produit modifié avec succès.');
            $redirectUrl = $request->query->get('redirect') ?? $this->generateUrl('admin_dashboard');
            return $this->redirect($redirectUrl);
        }
    } catch (\Exception $e) {
        return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
            'message' => 'Erreur modification produit : ' . $e->getMessage()
        ]);
    }

    return $this->render('admin/product_edit.html.twig', [
        'form' => $form->createView(),
        'product' => $product,
    ]);
}

    #[Route('/admin/product/{type}/{id}/delete', name: 'product_delete', methods: ['POST', 'GET'])]
    public function deleteProduct(string $type, int $id, BookRepository $bookRepo, FigurineRepository $figRepo, EntityManagerInterface $em, Request $request): Response
    {
        try {
            if ($type === 'book') {
                $product = $bookRepo->find($id);
            } else {
                $product = $figRepo->find($id);
            }

            if ($product) {
                $em->remove($product);
                $em->flush();
                $this->addFlash('success', 'Produit supprimé avec succès.');
            }
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur suppression produit : ' . $e->getMessage()
            ]);
        }

        $redirectUrl = $request->query->get('redirect') ?? $this->generateUrl('admin_dashboard');
        return $this->redirect($redirectUrl);
    }
}
