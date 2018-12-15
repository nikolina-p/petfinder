<?php

namespace App\Form;

use App\Entity\Photo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PhotoTransformer implements DataTransformerInterface
{

    /**
     * Transforms an array of Photos to an array of UploadedFile objects.
     *
     */
    public function transform($photos): ?ArrayCollection
    {
        if (count($photos) == 0) {
            return null;
        }

        array_walk($photos, function (&$photo) {
            $photo = $photo->getFile();
        });

        return $photos;
    }

    /**
     * Transforms a UploadedFile to a Photo.
     *
     */
    public function reverseTransform($uploadedFiles): ?ArrayCollection
    {
        if (count($uploadedFiles) == 0) {
            throw new TransformationFailedException(sprintf(
                'Please upload at least one photo of your pet!'
            ));
        }

        array_walk($uploadedFiles, function (&$file) {
            $photo = new Photo();
            $file = $photo->setFile($file);
        });

        return new ArrayCollection($uploadedFiles);
    }
}
