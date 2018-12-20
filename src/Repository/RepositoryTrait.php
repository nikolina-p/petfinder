<?php
namespace App\Repository;

use Doctrine\ORM\ORMException;

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

    public function delete(object $entity): bool
    {
        try {
            $entityManager = $this->getEntityManager();
            $entityManager->remove($entity);
            $entityManager->flush();
            return true;
        } catch (ORMException $e) {
            throw new ORMException("Error: Entity could not be deleted.");
        }
    }
}
