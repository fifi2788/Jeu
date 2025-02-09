<?php

namespace App\Service;

use Kreait\Firebase\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class FirebaseAuthenticationService
{
    private Auth $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function verifyToken(string $token): ?string
    {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($token);
            return $verifiedIdToken->claims()->get('sub'); 
        } catch (\Throwable $e) {
            throw new UnauthorizedHttpException('Bearer', 'Firebase token invalide.');
        }
    }
}
