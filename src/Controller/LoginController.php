<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as HasherUserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordHasherInterface;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // Suppose $plaintextPassword is the plain text password from the request
        $plaintextPassword = $request->request->get('password');
        $email = $request->request->get('email');

        // Find the user by email
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            // Handle user not found
            return new Response('User not found', 404);
        }

        // Check if the password is valid
        if ($passwordHasher->isPasswordValid($user, $plaintextPassword)) {
            // Handle successful login
            return new Response('Login successful');
        } else {
            // Handle invalid password
            return new Response('Invalid password', 401);
        }
    }
}
?>