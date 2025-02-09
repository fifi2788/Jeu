<?php

namespace App\Controller;

use App\Service\IngredientService;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FirebaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class IngredientController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private IngredientService $ingredientService;
    private IngredientRepository $ingredientRepository;
    private FirebaseService $firebaseService;
    

    public function __construct(IngredientService $ingredientService, IngredientRepository $ingredientRepository, EntityManagerInterface $entityManager, FirebaseService $firebaseService)
    {
        $this->ingredientService = $ingredientService;
        $this->ingredientRepository = $ingredientRepository;
        $this->entityManager = $entityManager;
        $this->firebaseService = $firebaseService;
    }

    #[Route('/api/v1/add/ingredients', name: 'add_ingredient', methods: ['POST'])]
    public function addIngredient(Request $request, SerializerInterface $serializer): JsonResponse
    {

        $token = $request->headers->get('Authorization');
        
        if (!$token || !str_starts_with($token, 'Bearer ')) {
            return $this->json(['error' => 'Token introuvable ou invalide!'], 401);
        }

        try {
            $firebaseToken = str_replace('Bearer ', '', $token);

            $decodedToken = $this->firebaseService->verifyIdToken($firebaseToken);

        } catch (\Exception $e) {
            return $this->json(['error' => 'Utilisateur non connecte: ' . $e->getMessage()], 401);
        }

        $nomIngredient = $request->request->get('nom_ingredient');
        $idUniteMesure = $request->request->get('id_unite_mesure');
        $imageFile = $request->files->get('url');
        $logo = $request->files->get('logo'); 

        if (!$logo) {
            return $this->json(['error' => 'Logo file est null!'], 400);
        }

        if (!$nomIngredient || !$idUniteMesure || !$imageFile || !$logo) {
            return $this->json(['error' => 'Tout les champs sont requis!'], 400);
        }

        try {
 
            $ingredient = $this->ingredientService->addIngredient($nomIngredient, $imageFile, $logo, $idUniteMesure);
            $jsonContent = $serializer->serialize($ingredient, 'json', ['groups' => ['ingredient:read']]);
            return new JsonResponse($jsonContent, 201, [], true);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    #[Route('/api/v1/update_ingredients/{id}', name: 'update_ingredient', methods: ['POST', 'PUT', 'PATCH'])]
    public function updateIngredient(Request $request, int $id): JsonResponse
    {

        $token = $request->headers->get('Authorization');
        
        if (!$token || !str_starts_with($token, 'Bearer ')) {
            return $this->json(['error' => 'Token introuvable ou invalide!'], 401);
        }

        try {
            $firebaseToken = str_replace('Bearer ', '', $token);

            $decodedToken = $this->firebaseService->verifyIdToken($firebaseToken);

        } catch (\Exception $e) {
            return $this->json(['error' => 'Utilisateur non connecte: ' . $e->getMessage()], 401);
        }

        $nomIngredient = $request->request->get('nom_ingredient');
        $idUniteMesure = $request->request->get('id_unite_mesure');
        $imageFile = $request->files->get('url'); 
        $logoFile = $request->files->get('logo'); 

        if (!$nomIngredient && !$idUniteMesure && !$imageFile && !$logoFile) {
            return $this->json(['error' => 'Aucun champ fourni pour la mise à jour.'], 400);
        }

        try {
            $ingredient = $this->ingredientService->updateIngredient($id, $nomIngredient, $idUniteMesure, $imageFile, $logoFile);
            return $this->json([
                'id' => $ingredient->getId(),
                'nomIngredient' => $ingredient->getNomIngredient(),
                'imgUrl' => $ingredient->getImgUrl(),
                'logo' => $ingredient->getLogo(),
                'id_unite_mesure' => $ingredient->getUniteMesure(),
            ], 200, [], ['groups' => 'ingredient:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }


    #[Route('/api/v1/test_connection', name: 'get_connection', methods: ['GET'])]
    public function getIngredients(Request $request): JsonResponse
    {
        $token = $request->headers->get('Authorization');
    
        if (!$token || !str_starts_with($token, 'Bearer ')) {
            return new JsonResponse(['error' => 'Token invalide'], 401);
        }
    
        try {
            // Remove "Bearer " from the token
            $idToken = str_replace('Bearer ', '', $token);
    
            // Call FirebaseService to verify the token and get all claims
            $decodedToken = $this->firebaseService->verifyIdToken($idToken);
    
            // Log the decoded token for further debugging
            error_log('Decoded Token: ' . json_encode($decodedToken));
    
            return new JsonResponse([
                'message' => 'Connected!',
                'user' => $decodedToken
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 401);
        }
    }
    
    
    

    #[Route('api/v1/get_list_ingredient', name: 'get_ingredients', methods: ['GET'])]
    public function listIngredients(): JsonResponse
    {
        // Retrieve all ingredients from the repository
        $ingredients = $this->ingredientRepository->findIngredient();

        // Convert ingredients to an array (or any structure that suits you)
        $data = [];
        foreach ($ingredients as $ingredient) {
            $data[] = [
                'id' => $ingredient->getId(),
                'nomIngredient' => $ingredient->getNomIngredient(),
                'imgUrl' => $ingredient->getImgUrl(),
                'logo' => $ingredient->getLogo(),
                'uniteMesure' => [
                    'id' => $ingredient->getUniteMesure()->getId(),
                    'nomUnite' => $ingredient->getUniteMesure()->getNomUnite()
                ]
            ];
        }

        // Return the ingredients as JSON
        return new JsonResponse($data);
    }
}
