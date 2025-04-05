<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    // Displays and processes the registration form
    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    public function registerForm(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();

        // Create and handle registration form
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // If form is submitted and valid, hash password and save user
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData())
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to login after successful registration
            return $this->redirectToRoute('login');
        }

        // Render the registration form
        return $this->render('register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
