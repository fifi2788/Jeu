<?php

namespace App\Service;

use App\Entity\Ingredient;
use App\Entity\UniteMesure;
use App\Repository\IngredientRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\UniteMesureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class IngredientService
{
    private IngredientRepository $ingredientRepository;
    private UniteMesureRepository $uniteMesureRepository;
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(IngredientRepository $ingredientRepository, UniteMesureRepository $uniteMesureRepository, Security $security, EntityManagerInterface $entityManager)
    {
        $this->ingredientRepository = $ingredientRepository;
        $this->uniteMesureRepository = $uniteMesureRepository;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function addIngredient(string $nomIngredient, UploadedFile $imgUrl, UploadedFile $logo, int $idUniteMesure): Ingredient
    {

        $unite = $this->uniteMesureRepository->find($idUniteMesure);
        if (!$unite) {
            throw new \InvalidArgumentException('Unite de mesure introuvable.');
        }

        $uploadDir = __DIR__ . '/../../assets/img/uploads/';
        $fileName = $imgUrl->getClientOriginalName(); 
        $filePath = $uploadDir . '/' . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath); 
        }

        $imgUrl->move($uploadDir, $fileName);
        $imgUrlPath = '/uploads/' . $fileName;

        $logoFileName = $logo->getClientOriginalName(); 
        $logoFilePath = $uploadDir . '/' . $logoFileName;

        if (file_exists($logoFilePath)) {
            unlink($logoFilePath); 
        }

        $logo->move($uploadDir, $logoFileName);
        $logoPath = '/uploads/' . $logoFileName;
        
        $ingredient = new Ingredient();
        $ingredient->setNomIngredient($nomIngredient);
        $ingredient->setImgUrl($imgUrlPath);
        $ingredient->setLogo($logoPath);
        $ingredient->setUniteMesure($unite);

        $this->entityManager->persist($ingredient);
        $this->entityManager->flush();

        return $ingredient;

    }

    public function updateIngredient(int $id, ?string $nomIngredient, ?int $idUniteMesure, ?UploadedFile $imageFile, ?UploadedFile $logoFile): ?Ingredient
    {

        $ingredient = $this->ingredientRepository->find($id);
        if (!$ingredient) {
            throw new \InvalidArgumentException('Ingredient not found!');
        }

        if ($nomIngredient !== null) {
            $ingredient->setNomIngredient($nomIngredient);
        }

        if ($idUniteMesure !== null) {
            $uniteMesure = $this->uniteMesureRepository->find($idUniteMesure);
            if (!$uniteMesure) {
                throw new \InvalidArgumentException('Unité de mesure introuvable!');
            }
            $ingredient->setUniteMesure($uniteMesure);
        }

        $uploadDir = __DIR__ . '/../../assets/img/uploads/';

        if ($imageFile !== null) {
            $fileName = $imageFile->getClientOriginalName(); 
            $imageFile->move($uploadDir, $fileName);
            $ingredient->setImgUrl('/uploads/' . $fileName);
        }

        if ($logoFile !== null) {
            $logoFileName = $logoFile->getClientOriginalName(); 
            $logoFile->move($uploadDir, $logoFileName);
            $ingredient->setLogo('/uploads/' . $logoFileName);
        }

        $this->entityManager->persist($ingredient);
        $this->entityManager->flush();

        return $ingredient;
    }
    

}
