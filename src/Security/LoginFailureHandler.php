<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class LoginFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $errorMessage = $exception->getMessageKey();

        return new JsonResponse([
            'success' => false,
            'message' => $errorMessage,
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }
}
