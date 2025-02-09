<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FirebaseAuthService;

class AuthController extends AbstractController
{
    private FirebaseAuthService $firebaseAuthService;

    public function __construct(FirebaseAuthService $firebaseAuthService)
    {
        $this->firebaseAuthService = $firebaseAuthService;
    }

    #[Route('/api/v1/register', name: 'register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;
            $role = $data['role'] ?? 'ROLE_USER';

            if (!$email || !$password) {
                return $this->json(['error' => 'Email et mot de passe requis'], 400);
            }

            $user = $this->firebaseAuthService->registerUser($email, $password, [$role]);
            
            return $this->json([
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'role' => $user->getRoles()
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/api/v1/auth/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return $this->json(['error' => 'Email et password requis'], 400);
        }

        try {
            $result = $this->firebaseAuthService->loginUser($email, $password);
            $user = $result['user'];
            $idToken = $result['idToken'];
            $refreshToken = $result['refreshToken'] ?? null;
            $expiresIn = $result['expiresIn'] ?? null;

            return $this->json([
                'user' => [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'role' => $user->getRoles(),
                ],
                'idToken' => $idToken,
                'refreshToken' => $refreshToken,
                'expiresIn' => $expiresIn
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
    

}

