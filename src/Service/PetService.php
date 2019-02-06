<?php

namespace App\Service;

use App\DTO\PetDTO;
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
        $this->photoService->uploadPhotos($pet->getPhotos());
        $this->petRepository->persist($pet);
    }

    public function loadPets(): array
    {
        return $this->petRepository->findAll();
    }

    public function findById(int $id): Pet
    {
        return $this->petRepository->find($id);
    }

    public function saveChanges(Pet $pet): void
    {
        $this->photoService->uploadPhotos($pet->getPhotos());
        $this->petRepository->saveChanges();
    }

    public function deletePhoto(string $photoName): void
    {
        $this->photoService->deletePhoto($photoName);
    }

    public function deletePet(Pet $pet): void
    {
        foreach ($pet->getPhotos() as $photo) {
            $this->deletePhoto($photo->getPhotoName());
        }
        $this->petRepository->delete($pet);
    }

    public function searchPets(PetDTO $petDTO): array
    {
        return $this->petRepository->searchPets($petDTO);
    }
}
