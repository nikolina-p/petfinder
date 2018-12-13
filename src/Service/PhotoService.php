<?php

namespace App\Service;

use App\Entity\Photo;
use App\Service\PhotoUploader;
use Doctrine\Common\Collections\ArrayCollection;

class PhotoService
{
    private $photoUploader;

    public function __construct( PhotoUploader $photoUploader)
    {
        $this->photoUploader = $photoUploader;
    }

    public function uploadPhotos(ArrayCollection $photoFiles): void
    {
        foreach ($photoFiles as $photo) {
            $fileName = $this->photoUploader->upload($photo->getFile());
            $photo->setPhotoName($fileName);
        }

    }
}