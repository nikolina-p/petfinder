<?php


namespace App\Service;

use App\Repository\PetRepository;
use App\Entity\Pet;


class PetService
{
    private $petRepository;
    private $photoService;

    public function __construct(PetRepository $petRepository, PhotoService $photoService)
    {
        $this->petRepository = $petRepository;
        $this->photoService = $photoService;
    }

    public function newPet(Pet $pet): void
    {
        var_dump($pet);
        $this->photoService->uploadPhotos($pet->getPhotos());
        $this->petRepository->persist($pet);
    }

}