<?php
namespace App\Controller;

use App\Entity\Book;
use App\Entity\Figurine;
use App\Entity\Order;
use App\Form\BookType;
use App\Form\FigurineType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(EntityManagerInterface $em): Response
    {
        $books = $em->getRepository(Book::class)->findAll();
        $figurines = $em->getRepository(Figurine::class)->findAll();
        $orders = $em->getRepository(Order::class)->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'books' => $books,
            'figurines' => $figurines,
            'orders' => $orders,
        ]);
    }

    #[Route('/admin/product/edit/{id}/{type}', name: 'admin_product_edit')]
    public function editProduct(int $id, string $type, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($type === 'book') {
            $product = $entityManager->getRepository(Book::class)->find($id);
            $form = $this->createForm(BookType::class, $product);
        } elseif ($type === 'figurine') {
            $product = $entityManager->getRepository(Figurine::class)->find($id);
            $form = $this->createForm(FigurineType::class, $product);
        } else {
            throw $this->createNotFoundException('Type de produit inconnu');
        }

        if (!$product) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/edit_product.html.twig', [
            'form' => $form->createView(),
            'productType' => $type
        ]);
    }

    #[Route('/admin/book/delete/{id}', name: 'admin_book_delete')]
    public function deleteBook(Book $book, EntityManagerInterface $em): Response
    {
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/admin/figurine/delete/{id}', name: 'admin_figurine_delete')]
    public function deleteFigurine(Figurine $figurine, EntityManagerInterface $em): Response
    {
        $em->remove($figurine);
        $em->flush();
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/admin/order/update/{id}', name: 'admin_order_update')]
    public function updateOrderStatus(Order $order, EntityManagerInterface $em): Response
    {
        $statusFlow = ['Préparation de commande', 'Commande en cours', 'Commande prête', 'Commande terminée'];
        $currentStatus = array_search($order->getStatus(), $statusFlow);
        $newStatus = $statusFlow[($currentStatus + 1) % count($statusFlow)];

        $order->setStatus($newStatus);
        $em->flush();

        return $this->redirectToRoute('admin_dashboard');
    }
}
