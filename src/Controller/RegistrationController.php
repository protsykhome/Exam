<?php
namespace App\Controller;

use App\Service\AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @Route("/api/register", name="api_register", methods={"POST"})
     */
    public function register(Request $request): JsonResponse
    {
        // Extract user registration data from the request
        $userData = $this->extractUserData($request);

        $user = $this->authService->registerUser($userData['username'], $userData['email'], $userData['password']);

        return new JsonResponse([
            'message' => 'User registered successfully',
            'user' => [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                // Additional user data...
            ]
        ]);
    }

    private function extractUserData(Request $request): array
    {
        // Extract and validate user registration data from the request
        $data = json_decode($request->getContent(), true);

        return [
            'username' => $data['username'] ?? null,
            'email' => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
            // Additional user data...
        ];
    }
}
