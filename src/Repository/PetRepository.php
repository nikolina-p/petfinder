<?php

namespace App\Repository;

use App\DTO\PetDTO;
use App\Entity\Pet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Pet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pet[]    findAll()
 * @method Pet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
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
        $parameters = [];
        $queryBuilder->select('p')
            ->from('App:Pet', 'p');

        if ($petDTO->getSpecies() !== null) {
            $parameters['species'] = $petDTO->getSpecies()->getId();
            $queryBuilder->where('p.species = :species');
        }

        if ($petDTO->getBreed() !== null) {
            $parameters['breed'] = '%'.$petDTO->getBreed().'%';
            $queryBuilder->andWhere('p.breed LIKE :breed');
        }

        if ($petDTO->getGender() !== null) {
            $parameters['gender'] = $petDTO->getGender();
            $queryBuilder->andWhere('p.gender = :gender');
        }

        if ($petDTO->getAge() !== null) {
            $parameters['age'] = $petDTO->getAge();
            $queryBuilder->andWhere('p.age = :age');
        }

        $queryBuilder->setParameters($parameters);

        return $queryBuilder->getQuery()->getResult();
    }
}
