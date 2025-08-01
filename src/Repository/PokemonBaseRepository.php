<?php

namespace App\Repository;

use App\Entity\PokemonBase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PokemonBaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonBase::class);
    }

    public function sortByAlphab(): array
    {
        $entityManager = $this->getEntityManager();
        $dql = "SELECT p FROM App\Entity\PokemonBase p
              ORDER BY p.nom ASC";
        $query = $entityManager->createQuery($dql);

        return $query->getResult();
    }

    public function sortByCapture(): array
    {
        $entityManager = $this->getEntityManager();
        $dql = "SELECT p FROM App\Entity\PokemonBase p
              ORDER BY p.est_capture DESC";
        $query = $entityManager->createQuery($dql);

        return $query->getResult();
    }

    public function filterCaptured(): array
    {
        $entityManager = $this->getEntityManager();
        $dql = "SELECT p FROM App\Entity\PokemonBase p
                WHERE p.est_capture = 1
              ORDER BY p.nom ASC";
        $query = $entityManager->createQuery($dql);

        return $query->getResult();
    }

    public function sortByAttack(): array
    {
        $entityManager = $this->getEntityManager();
        $dql = "SELECT p FROM App\Entity\PokemonBase p
              ORDER BY p.attaque DESC";
        $query = $entityManager->createQuery($dql);

        return $query->getResult();
    }
}
