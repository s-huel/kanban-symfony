<?php

namespace App\Controller;

use App\DTO\RegistrationRequestDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(
        #[MapRequestPayload] RegistrationRequestDTO $registrationRequest,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse {
        $errors = $validator->validate($registrationRequest);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        if ($entityManager->getRepository(User::class)->findOneBy(['email' => $registrationRequest->email])) {
            return $this->json(['error' => 'Email already exists'], 400);
        }

        $user = new User();
        $user->setEmail($registrationRequest->email);
        $user->setPassword(
            $passwordHasher->hashPassword($user, $registrationRequest->password)
        );

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ], 201);
    }
}