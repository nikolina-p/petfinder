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

    public function loadSpecies()
    {
        return $this->speciesRepository->findAll();
    }

    public function newSpecies(Species $species)
    {
        $this->speciesRepository->persist($species);
    }

    public function editSpecies()
    {
        $this->speciesRepository->saveChanges();
    }

    public function deleteSpecies(Species $species)
    {
        $this->speciesRepository->delete($species);
    }

    public function findById(int $id)
    {
        $this->speciesRepository->find($id);
    }
}
