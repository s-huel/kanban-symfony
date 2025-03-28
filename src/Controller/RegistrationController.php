<?php

namespace App\Controller;

use App\DTO\UserRegistrationDTO;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'api_register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator): Response
    {
        $dto = new UserRegistrationDTO();

        $form = $this->createForm(RegistrationFormType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $validator->validate($dto);
            if (count($errors) > 0) {
                return $this->render('registration/register.html.twig', [
                    'form' => $form->createView(),
                    'errors' => $errors,
                ]);
            }

            $user = new User();
            $user->setName($dto->name);
            $user->setEmail($dto->email);
            $user->setPassword($passwordHasher->hashPassword($user, $dto->password));

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Registration successful! Please log in.');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
