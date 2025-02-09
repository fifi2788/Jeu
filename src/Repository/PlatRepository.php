<?php

namespace App\Repository;

use App\Entity\Plat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PlatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plat::class);
    }

    public function save(Plat $plat): void
    {
        $this->_em->persist($plat);
        $this->_em->flush();
    }

    public function findPlatById(int $id): ?Plat
    {
        return $this->find($id);
    }

    public function findAllPlat(): array
    {
        return $this->findAll();
    }
}
