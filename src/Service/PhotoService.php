<?php

namespace App\Service;

use App\Entity\Photo;
use App\Entity\Pet;
use App\Service\PhotoUploader;
use App\Repository\PhotoRepository;
use Doctrine\Common\Collections\ArrayCollection;

class PhotoService
{
    private $photoRepository;
    private $photoUploader;

    public function __construct(PhotoRepository $photoRepository, PhotoUploader $photoUploader)
    {
        $this->photoRepository = $photoRepository;
        $this->photoUploader = $photoUploader;
    }

    public function uploadPhotos(ArrayCollection $photoFiles): void
    {
        foreach ($photoFiles as $photo)
        {
            $fileName = $this->photoUploader->upload($photo->getFile());
            $photo->setPhotoName($fileName);
            $this->photoRepository->persist($photo);
        }
    }
}