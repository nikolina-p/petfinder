<?php

namespace App\Service;

use App\Entity\Photo;
use App\Repository\PhotoRepository;
use Doctrine\Common\Collections\ArrayCollection;

class PhotoService
{
    private $photoFileManager;

    private $photoRepository;

    public function __construct(PhotoFileManager $photoFileManager, PhotoRepository $photoRepository)
    {
        $this->photoFileManager = $photoFileManager;
        $this->photoRepository = $photoRepository;
    }

    public function uploadPhotos(ArrayCollection $photoFiles): void
    {
        foreach ($photoFiles as $photo) {
            if ($photo->getFile() != null) {
                $fileName = $this->photoFileManager->upload($photo->getFile());
                $photo->setPhotoName($fileName);
            }
        }
    }

    public function deletePhoto(string $photoName): void
    {
        $photo = $this->photoRepository->findOneBy(['photoName' => $photoName]);
        $this->photoFileManager->deleteFile($photo->getPhotoName());
        $this->photoRepository->delete($photo);
    }
}
