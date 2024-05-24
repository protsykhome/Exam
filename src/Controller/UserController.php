<?php

namespace App\Controller;

use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'];
        $email = $data['email'];
        $plainPassword = $data['password'];

        $this->authService->registerUser($username, $email, $plainPassword);

        return new Response('User registered successfully');
    }
}
