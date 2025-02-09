<?php

namespace App\Controller;

use App\Service\StockIngredientService;
use App\Repository\StockIngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FirebaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class StockIngredientController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private StockIngredientService $ingredientService;
    private StockIngredientRepository $ingredientRepository;
    private FirebaseService $firebaseService;
    

    public function __construct(StockIngredientService $stockIngredientService, StockIngredientRepository $stockIngredientRepository, EntityManagerInterface $entityManager, FirebaseService $firebaseService)
    {
        $this->stockIngredientService = $stockIngredientService;
        $this->stockIngredientRepository = $stockIngredientRepository;
        $this->entityManager = $entityManager;
        $this->firebaseService = $firebaseService;
    }

    /*#[Route('/api/v1/add/stock_ingredients', name: 'add_stock_ingredient', methods: ['POST'])]
    public function addStockIngredient(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $idIngredient = $request->request->get('id_ingredient');
        $valeurEntree = $request->request->get('entree');

        if (!$idIngredient || !$valeurEntree) {
            return $this->json(['error' => 'Tout les champs sont requis!'], 400);
        }

        try {
 
            $stockIngredient = $this->stockIngredientService->addStockIngredient($idIngredient, $valeurEntree);
            $jsonContent = $serializer->serialize($ingredient, 'json', ['groups' => ['stockIngredient:read']]);
            return new JsonResponse($jsonContent, 201, [], true);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }*/

    #[Route('/api/v1/add/stock_ingredients', name: 'add_stock', methods: ['POST'])]
    public function addStock(Request $request, SerializerInterface $serializer): JsonResponse
    {

        date_default_timezone_set('Africa/Nairobi');

        $data = json_decode($request->getContent(), true);

        if (!isset($data['id_ingredient']) || !isset($data['valeur_entree'])) {
            return new JsonResponse(['error' => 'id_ingredient and valeur_entree are required'], 400);
        }

        try {
            $stockIngredient = $this->stockIngredientService->addStockIngredient(
                $data['id_ingredient'],
                $data['valeur_entree']
            );

            $jsonContent = $serializer->serialize($stockIngredient, 'json', ['groups' => 'stockIngredient:read']);
            return new JsonResponse($jsonContent, 201, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    
    #[Route('/api/v1/update_stock_ingredients/{id}', name: 'update_stock_ingredient', methods: ['POST', 'PUT', 'PATCH'])]
    public function updateStockIngredient(Request $request, int $id): JsonResponse
    {

        $idIngredient = $request->request->get('id_ingredient');
        $valeurEntree = $request->request->get('entree');
        $valeurSortie = $request->request->get('sortie'); 

        if (!$idIngredient && !$valeurEntree && !$valeurSortie) {
            return $this->json(['error' => 'Aucun champ fourni pour la mise à jour.'], 400);
        }

        try {
            $stockIngredient = $this->stockIngredientService->updateStockIngredient($id, $valeurEntree, $valeurSortie);
            return $this->json([
                'id_stock_ingredient' => $stockIngredient->getId(),
                'id_ingredient' => $stockIngredient->getIngredient(),
                'valeur_entree' => $stockIngredient->getValeurEntree(),
                'valeur_sortie' => $stockIngredient->getValeurSortie(),
            ], 200, [], ['groups' => 'stockIngredient:read']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    

    #[Route('api/v1/get_list_stock_ingredient', name: 'get_stock_ingredients', methods: ['GET'])]
    public function listStockIngredients(): JsonResponse
    {
        $stockIngredients = $this->stockIngredientRepository->findAllStockIngredient();

        $data = [];
        foreach ($stockIngredients as $stockIngredient) {
            $data[] = [
                'id' => $stockIngredient->getId(),
                'ingredient' => [
                    'id' => $stockIngredient->getIngredient()->getId(),
                    'nomUnite' => $stockIngredient->getIngredient()->getNomIngredient(),
                    'nomUnite' => $stockIngredient->getIngredient()->getUniteMesure()->getNomUnite()
                ],
                'valeur_entree' => $stockIngredient->getValeurEntree(),
                'valeur_sortie' => $stockIngredient->getValeurSortie(),
                'date_entree' => $stockIngredient->getDateMouvement()
            ];
        }

        return new JsonResponse($data);
    }
}
