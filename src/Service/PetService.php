<?php


namespace App\Service;

use App\Repository\PetRepository;
use App\Entity\Pet;


class PetService
{
    private $petRepository;

    public function __construct(PetRepository $petRepository)
    {
        $this->petRepository = $petRepository;
    }

    public function newPet(Pet $pet): void
    {
        $this->petRepository->persist($pet);
    }

}