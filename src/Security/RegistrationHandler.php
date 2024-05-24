<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationHandler
{
    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function register(Request $request): JsonResponse
    {
        $userData = json_decode($request->getContent(), true);

        $user = new User();
        $user->setUsername($userData['email']); // Assuming you are using email as username
        $user->setEmail($userData['email']);

        $encodedPassword = $this->passwordEncoder->encodePassword($user, $userData['password']);
        $user->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'User registered successfully',
            'user' => [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
            ]
        ]);
    }
}
