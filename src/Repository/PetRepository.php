<?php

namespace App\Repository;

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

    public function searchPets(Pet $pet)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('p')
            ->from('App:Pet', 'p');
        if ($pet->getSpecies() !== null) $queryBuilder->where($queryBuilder->expr()->eq(
            'p.species',
            "'".$pet->getSpecies()->getId()."'"
        ));
        if ($pet->getBreed() !== null) $queryBuilder->andWhere($queryBuilder->expr()->like(
            'p.breed',
            "'%".$pet->getBreed()."%'"
        ));
        if ($pet->getGender() !== null) $queryBuilder->andWhere($queryBuilder->expr()->eq(
            'p.gender',
            "'".$pet->getGender()."'"
        ));
        if ($pet->getAge() !== null) $queryBuilder->andWhere($queryBuilder->expr()->eq(
            'p.age',
            $pet->getAge()
        ));
        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        return $result;
    }
}
