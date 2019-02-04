<?php

namespace App\Service;

use App\Exception\EntityNotDeletedException;
use App\Repository\SpeciesRepository;
use App\Entity\Species;

class SpeciesService
{
    private $speciesRepository;

    public function __construct(SpeciesRepository $speciesRepository)
    {
        $this->speciesRepository = $speciesRepository;
    }

    public function loadSpecies(): array
    {
        return $this->speciesRepository->findAll();
    }

    public function newSpecies(Species $species): void
    {
        $this->speciesRepository->persist($species);
    }

    public function editSpecies(): void
    {
        $this->speciesRepository->saveChanges();
    }

    public function deleteSpecies(Species $species): void
    {
        $this->speciesRepository->delete($species);
    }
}
