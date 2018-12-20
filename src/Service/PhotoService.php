<?php

namespace App\Service;

use App\Entity\Photo;
use App\Entity\Pet;
use App\Repository\PhotoRepository;
use App\Service\PhotoUploader;
use Doctrine\Common\Collections\ArrayCollection;

class PhotoService
{
    private $photoUploader;

    private $photoRepository;

    public function __construct(PhotoUploader $photoUploader, PhotoRepository $photoRepository)
    {
        $this->photoUploader = $photoUploader;
        $this->photoRepository = $photoRepository;
    }

    public function uploadPhotos(ArrayCollection $photoFiles): void
    {
        foreach ($photoFiles as $photo) {
            if ($photo->getFile() != null) {
                $fileName = $this->photoUploader->upload($photo->getFile());
                $photo->setPhotoName($fileName);
            }
        }
    }

    public function deletePhoto(string $photoName): bool
    {
        $photo = $this->photoRepository->findBy(['photoName' => $photoName]);
        $photo = $photo[0];

        return $this->photoUploader->deleteFile($photo->getPhotoName()) &&
        $this->photoRepository->delete($photo);
    }

    public function loadByPhotoName(string $photoName): Photo
    {
        return $this->photoRepository->loadByPhotoName($photoName);
    }
}
