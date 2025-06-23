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

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Form\ProfileType;
use App\Form\EmailType;
use App\Form\PasswordType;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Knp\Component\Pager\PaginatorInterface;

class ProfileController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_profile')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        PaginatorInterface $paginator
    ): Response {
        try {
            /** @var User $user */
            $user = $this->getUser();

            // PROFIL
            $profileForm = $this->createForm(ProfileType::class, $user);
            $profileForm->handleRequest($request);
            if ($profileForm->isSubmitted()) {
                if ($profileForm->isValid()) {
                    $picture = $profileForm->get('pictureUser')->getData();
                    if ($picture) {
                        $user->setPictureUser($picture);
                    }
                    $em->flush();

                    return $this->handleResponse($request, true, 'Profil mis à jour.');
                }

                return $this->handleResponse($request, false, 'Formulaire profil invalide.', $profileForm);
            }

            // EMAIL
            $emailForm = $this->createForm(EmailType::class, $user);
            $emailForm->handleRequest($request);
            if ($emailForm->isSubmitted()) {
                if ($emailForm->isValid()) {
                    $em->flush();
                    return $this->handleResponse($request, true, 'Email modifié.');
                }

                return $this->handleResponse($request, false, 'Formulaire email invalide.', $emailForm);
            }

            // PASSWORD
            $passwordForm = $this->createForm(PasswordType::class);
            $passwordForm->handleRequest($request);
            if ($passwordForm->isSubmitted()) {
                if ($passwordForm->isValid()) {
                    $current = $passwordForm->get('current_password')->getData();
                    $new = $passwordForm->get('new_password')->getData();

                    if (!$hasher->isPasswordValid($user, $current)) {
                        return $this->handleResponse($request, false, 'Mot de passe actuel incorrect.');
                    }

                    $user->setPassword($hasher->hashPassword($user, $new));
                    $em->flush();
                    return $this->handleResponse($request, true, 'Mot de passe mis à jour.');
                }

                return $this->handleResponse($request, false, 'Formulaire mot de passe invalide.', $passwordForm);
            }

            // ADRESSE
            $addressForm = $this->createForm(AddressType::class, $user);
            $addressForm->handleRequest($request);
            if ($addressForm->isSubmitted()) {
                if ($addressForm->isValid()) {
                    $em->flush();
                    return $this->handleResponse($request, true, 'Adresse mise à jour.');
                }

                return $this->handleResponse($request, false, 'Formulaire adresse invalide.', $addressForm);
            }

            // Commandes sur 3 mois
            $threeMonthsAgo = new \DateTimeImmutable('-3 months');
            $query = $em->getRepository(Order::class)
                ->createQueryBuilder('o')
                ->where('o.user = :user')
                ->andWhere('o.createdAt >= :date')
                ->setParameter('user', $user)
                ->setParameter('date', $threeMonthsAgo)
                ->orderBy('o.createdAt', 'DESC')
                ->getQuery();

            $orders = $paginator->paginate($query, $request->query->getInt('page', 1), 5);

            return $this->render('account/dashboard.html.twig', [
                'profileForm' => $profileForm->createView(),
                'emailForm' => $emailForm->createView(),
                'passwordForm' => $passwordForm->createView(),
                'addressForm' => $addressForm->createView(),
                'orders' => $orders,
            ]);
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur chargement du compte : ' . $e->getMessage()
            ]);
        }
    }

    private function handleResponse(Request $request, bool $success, string $message, $form = null): Response
    {
        if ($request->isXmlHttpRequest()) {
            $response = ['success' => $success, 'message' => $message];

            if (!$success && $form) {
                $response['errors'] = $this->getFormErrors($form);
            }

            return new JsonResponse($response, $success ? 200 : 400);
        }

        $this->addFlash($success ? 'success' : 'error', $message);
        return $this->redirectToRoute('app_profile');
    }

    private function getFormErrors($form): array
    {
        $errors = [];

        foreach ($form->all() as $child) {
            foreach ($child->getErrors(true) as $error) {
                $errors[$child->getName()][] = $error->getMessage();
            }
        }

        return $errors;
    }



    #[Route('/mon-compte/commande/{id}/pdf', name: 'account_order_pdf')]
    public function generateOrderPdf(Order $order): Response
    {
        try {
            $user = $this->getUser();
            if ($order->getUser() !== $user) {
                throw $this->createAccessDeniedException("Accès refusé à cette commande.");
            }

            $pdf = new \FPDF();
            $pdf->AddPage();

            $pdf->Image(__DIR__ . '/../../public/uploads/logo.png', 10, 10, 40);
            $pdf->SetXY(130, 15);
            $pdf->SetFont('Arial', '', 12);
            $pdf->MultiCell(0, 6, utf8_decode("Manga-Wara\n3 square des sports\n75000 Paris\ncontact@entreprise.com"), 0, 'R');

            $pdf->SetXY(10, 50);
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, utf8_decode("Bon de commande n°" . $order->getId()), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, 'Date : ' . $order->getCreatedAt()->format('d/m/Y'), 0, 1);
            $pdf->Ln(5);

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(80, 10, 'Produit', 1);
            $pdf->Cell(30, 10, 'Quantité', 1);
            $pdf->Cell(40, 10, 'Prix U.', 1);
            $pdf->Cell(40, 10, 'Total', 1);
            $pdf->Ln();

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

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(150, 10, 'Total', 1);
            $pdf->Cell(40, 10, number_format($total, 2), 1, 0, 'C');

            return new Response($pdf->Output('', 'I'), 200, [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (\Exception $e) {
            return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
                'message' => 'Erreur génération PDF commande : ' . $e->getMessage()
            ]);
        }
    }
}