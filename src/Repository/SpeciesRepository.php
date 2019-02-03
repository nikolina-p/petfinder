<?php

namespace App\Repository;

use App\Entity\Species;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SpeciesRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Species::class);
    }
}
