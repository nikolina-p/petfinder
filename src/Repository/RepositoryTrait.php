<?php
namespace App\Repository;

use App\Exception\EntityNotDeletedException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Config\Definition\Exception\Exception;

trait RepositoryTrait
{
    public function persist(object $entity): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($entity);
        $entityManager->flush();
    }

    public function saveChanges(): void
    {
        $this->getEntityManager()->flush();
    }

    public function delete(object $entity): void
    {
        try {
            $entityManager = $this->getEntityManager();
            $entityManager->remove($entity);
            $entityManager->flush();
        } catch (ORMException $e) {
            throw new EntityNotDeletedException("Error: Entity could not be deleted.");
        }
    }
}
