<?php

namespace App\Service;

use App\Entity\StockIngredient;
use App\Entity\Ingredient;
use App\Repository\StockIngredientRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use \DateTime;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StockIngredientService
{
    private StockIngredientRepository $stockIngredientRepository;
    private IngredientRepository $ingredientRepository;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(StockIngredientRepository $stockIngredientRepository, IngredientRepository $ingredientRepository, Security $security, EntityManagerInterface $entityManager)
    {
        $this->stockIngredientRepository = $stockIngredientRepository;
        $this->ingredientRepository = $ingredientRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function addStockIngredient(int $idIngredient, float $valeurEntree): StockIngredient
    {

        $ingredient = $this->ingredientRepository->find($idIngredient);
        if (!$ingredient) {
            throw new \InvalidArgumentException('Ingredient introuvable.');
        }
        
        $stockIngredient = new StockIngredient();
        $stockIngredient->setIngredient($ingredient);
        $stockIngredient->setValeurEntree($valeurEntree);
        $stockIngredient->setValeurSortie(0);

        $dateMouvement = new \DateTime('now', new \DateTimeZone('Africa/Nairobi'));

        $stockIngredient->setDateMouvement($dateMouvement);

        $this->entityManager->persist($stockIngredient);
        $this->entityManager->flush();

        return $stockIngredient;

    }

    public function updateStockIngredient(int $id, ?int $idIngredient, ?float $valeurEntree, ?float $valeurSortie): ?StockIngredient
    {

        $stockIngredient = $this->stockIngredientRepository->find($id);
        if (!$stockIngredient) {
            throw new \InvalidArgumentException('Stock d\'ingredient ingredient!');
        }

        if ($idIngredient !== null) {
            $ingredient = $this->ingredientRepository->find($idIngredient);
            if (!$ingredient) {
                throw new \InvalidArgumentException('Ingredient introuvable!');
            }
            $stockIngredient->setIngredient($ingredient);
        }

        if ($valeurEntree !== null) {
            $stockIngredient->setValeurEntree($valeurEntree);
        }

        if ($valeurSortie !== null) {
            $stockIngredient->setValeurSortie($valeurSortie);
        }

        $this->entityManager->persist($ingredient);
        $this->entityManager->flush();

        return $stockIngredient;
    }
    

}
