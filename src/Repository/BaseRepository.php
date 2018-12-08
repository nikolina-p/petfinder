<?php
namespace App\Repository;

trait BaseRepository
{
    public function persist(object $entity): void
    {
        $entityManager = $this->getEntityManager();;
        $entityManager->persist($entity);
        $entityManager->flush();
    }
}