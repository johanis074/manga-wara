<?php
namespace App\Controller;

use App\Entity\Book;
use App\Entity\Figurine;
use App\Entity\Order;
use App\Entity\User;
use App\Form\BookType;
use App\Form\FigurineType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function adminDashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'body_class' => 'admin-page'
        ]);
    }

    #[Route('/admin/user', name: 'admin_user')]
    public function userDashboard(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/user.html.twig', [
            'users' => $users,
            'body_class' => 'admin-page'
        ]);
    }

    #[Route('/admin/order', name: 'admin_order')]
    public function orderDashboard(): Response
    {
        return $this->render('admin/order.html.twig', [
            'body_class' => 'admin-page'
        ]);
    }

    #[Route('/admin/product', name: 'admin_product')]
    public function manageProducts(EntityManagerInterface $em): Response
    {
        $books = $em->getRepository(Book::class)->findAll();
        $figurines = $em->getRepository(Figurine::class)->findAll();

        return $this->render('admin/product.html.twig', [
            'books' => $books,
            'figurines' => $figurines,
            'body_class' => 'admin-page'
        ]);
    }
}
