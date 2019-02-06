<?php

namespace App\Repository;

use App\DTO\PetDTO;
use App\Entity\Pet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PetRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pet::class);
    }

    public function searchPets(PetDTO $petDTO)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('p')
            ->from('App:Pet', 'p');

        if ($petDTO->hasSpecies()) {
            $queryBuilder->where('p.species = :species')
                ->setParameter('species', $petDTO->getSpeciesId());
        }

        if ($petDTO->hasBreed()) {
            $queryBuilder->andWhere('p.breed LIKE :breed')
                ->setParameter('breed', "%".$petDTO->getBreed()."%");
        }

        if ($petDTO->hasGender()) {
            $queryBuilder->andWhere('p.gender = :gender')
                ->setParameter('gender', $petDTO->getGender());
        }

        if ($petDTO->hasAge()) {
            $queryBuilder->andWhere('p.age = :age')
                ->setParameter('age', $petDTO->getAge());
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
