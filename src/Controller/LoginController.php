<?php

namespace App\Controller;

use App\DTO\UserLoginDTO;
use App\Entity\User;
use App\Form\LoginFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class LoginController extends AbstractController
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(
        Request $request, EntityManagerInterface $entityManager, UserAuthenticatorInterface $userAuthenticator
    ): Response {
        $dto = new UserLoginDTO();
        $form = $this->createForm(LoginFormType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $dto->email]);

            if (!$user || !password_verify($dto->password, $user->getPassword())) {
                $this->addFlash('error', 'Invalid credentials.');
                return $this->redirectToRoute('login');
            }

            $currentRequest = $this->requestStack->getCurrentRequest();
            $userAuthenticator->authenticateUser($user, $currentRequest);

        }

        return $this->render('login/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
