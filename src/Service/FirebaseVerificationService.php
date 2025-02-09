<?php

namespace App\Service;

use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Factory;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class FirebaseVerificationService
{
    private Auth $auth;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/../../config/firebase_credentials.json');
        $this->auth = $factory->createAuth();
    }

    public function verifyToken(string $idToken): array
    {
        try {
            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            return $verifiedIdToken->claims()->all();
        } catch (FailedToVerifyToken $e) {
            throw new UnauthorizedHttpException('Bearer', 'Firebase token invalide.');
        }
    }
}
