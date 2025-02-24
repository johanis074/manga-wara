<?php

// namespace App\Controller;

// use App\Service\StripeService;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// class StripeController extends AbstractController
// {
//     #[Route('/checkout', name: 'stripe_checkout', methods: ['POST'])]
//     public function checkout(StripeService $stripeService, Request $request): JsonResponse
//     {
//         $data = json_decode($request->getContent(), true);

//         $session = $stripeService->createCheckoutSession([
//             'price_data' => [
//                 'currency' => 'eur',
//                 'product_data' => ['name' => $data['product_name']],
//                 'unit_amount' => $data['price'] * 100, // Convertir en centimes
//             ],
//             'quantity' => $data['quantity'],
//         ]);

//         return new JsonResponse(['id' => $session->id]);
//     }
//     #[Route('/payment/status', name: 'payment_status')]
//     public function paymentStatus(Request $request, StripeService $stripeService): Response
//     {
//         $sessionId = $request->query->get('session_id');

//         if (!$sessionId) {
//             return $this->render('payment/status.html.twig', ['status' => 'error']);
//         }

//         $session = $stripeService->retrieveSession($sessionId);

//         return $this->render('payment/status.html.twig', [
//             'status' => $session->payment_status === 'paid' ? 'success' : 'error',
//             'amount' => $session->amount_total / 100, // Convertir en euros
//             'currency' => strtoupper($session->currency),
//     ]);
// }
// } 
