<?php
namespace App\Repository;

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
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
    }

}
