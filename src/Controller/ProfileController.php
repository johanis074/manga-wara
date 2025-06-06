<?php
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
use FPDF;

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

        $profileForm = $this->createForm(ProfileType::class, $user);
        $profileForm->handleRequest($request);
        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            $picture = $profileForm->get('pictureUser')->getData();
            if ($picture) {
                $user->setPictureUser($picture); // c'est juste une string, pas besoin de traitement
            }
            $em->flush();
            $this->addFlash('success', 'Profil mis à jour.');
            return $this->redirectToRoute('app_profile');
        }

        $emailForm = $this->createForm(EmailType::class, $user);
        $emailForm->handleRequest($request);
        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Email modifié.');
            return $this->redirectToRoute('app_profile');
        }

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
                return $this->redirectToRoute('app_profile');
            }
        }

        $addressForm = $this->createForm(AddressType::class);
        $addressForm->handleRequest($request);
        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            if (!$addressForm->get('deliveryDifferent')->getData()) {
                $user->setDeliveryAddress(null);
            }
            $em->flush();
            $this->addFlash('success', 'Adresses mises à jour.');
            return $this->redirectToRoute('app_profile');
        }

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

    #[Route('/mon-compte/commande/{id}/pdf', name: 'account_order_pdf')]
    public function generateOrderPdf(Order $order): Response
    {
        $user = $this->getUser();

        // Sécurité : le PDF ne doit être accessible que par le propriétaire
        if ($order->getUser() !== $user) {
            throw $this->createAccessDeniedException("Accès refusé à cette commande.");
        }

        $pdf = new \FPDF();
        $pdf->AddPage();

        // Logo et en-tête
        $pdf->Image(__DIR__ . '/../../public/uploads/logo.png', 10, 10, 40);
        $pdf->SetXY(130, 15);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 6, utf8_decode("Manga-Wara\n3 square des sports\n75000 Paris\ncontact@entreprise.com"), 0, 'R');

        // Titre
        $pdf->SetXY(10, 50);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode("Bon de commande n°" . $order->getId()), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Date : ' . $order->getCreatedAt()->format('d/m/Y'), 0, 1);
        $pdf->Ln(5);

        // En-tête tableau
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 10, 'Produit', 1);
        $pdf->Cell(30, 10, 'Quantité', 1);
        $pdf->Cell(40, 10, 'Prix U.', 1);
        $pdf->Cell(40, 10, 'Total', 1);
        $pdf->Ln();

        // Contenu commande
        $pdf->SetFont('Arial', '', 12);
        $total = 0;

            foreach ($order->getProducts() as $item) {
        $name = $item['name'] ?? 'Produit inconnu';
        $quantity = $item['quantity'];
        $price = $item['price'];
        $lineTotal = $quantity * $price;
        $total += $lineTotal;

        $pdf->Cell(80, 10, utf8_decode($name), 1);
        $pdf->Cell(30, 10, $quantity, 1, 0, 'C');
        $pdf->Cell(40, 10, number_format($price, 2), 1, 0, 'C');
        $pdf->Cell(40, 10, number_format($lineTotal, 2), 1, 0, 'C');
        $pdf->Ln();
    }

        // Total final
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(150, 10, 'Total', 1);
        $pdf->Cell(40, 10, number_format($total, 2), 1, 0, 'C');

        return new Response($pdf->Output('', 'I'), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

}
