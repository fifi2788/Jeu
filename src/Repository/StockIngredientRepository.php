<?php

namespace App\Repository;

use App\Entity\StockIngredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StockIngredientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockIngredient::class);
    }

    public function save(StockIngredient $stockIngredient): void
    {
        $this->_em->persist($stockIngredient);
        $this->_em->flush();
    }

    public function findStockIngredientById(int $id): ?StockIngredient
    {
        return $this->find($id);
    }

    public function findAllStockIngredient(): array
    {
        return $this->findAll();
    }
}
