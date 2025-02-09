<?php

namespace App\Repository;

use App\Entity\UniteMesure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UniteMesureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UniteMesure::class);
    }

    public function findUniteMesureById(int $id): ?UniteMesure
    {
        return $this->find($id);
    }

    public function findAllUniteMesures(): array
    {
        return $this->findAll();
    }
}
