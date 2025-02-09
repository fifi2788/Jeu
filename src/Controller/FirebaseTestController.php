<?php

namespace App\Controller;

use Kreait\Firebase\Auth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FirebaseTestController extends AbstractController
{
    #[Route('/api/test-firebase', name: 'test_firebase', methods: ['GET'])]
    public function testFirebase(Auth $auth): JsonResponse
    {
        try {

            return $this->json([
                'status' => 'success',
                'message' => 'Firebase is configured correctly!',
                'auth' => get_class($auth), 
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
