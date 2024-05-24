<?php

// src/Controller/LoginController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): JsonResponse
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            return $this->json([
                'success' => false,
                'message' => $error->getMessageKey(),
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'success' => true,
            'message' => 'Login successful!',
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
            ],
        ]);
    }
}

