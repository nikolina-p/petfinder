<?php

namespace App\Form;

use App\Entity\Pet;
use Symfony\Component\Form\DataTransformerInterface;

class PetGenderTransformer implements  DataTransformerInterface
{
    /**
     * Transforms an array of Photos to an array of UploadedFile objects.
     */
    public function transform($gender)
    {


        return $gender;
    }

    /**
     * Transforms a UploadedFile to a Photo.
     */
    public function reverseTransform($gender): string
    {
        /**
         *
        $genders = ['Male', 'Female', 'Unknown'];

        if ($gender === null) {
        return $genders;
        }

        array_unshift($genders, $gender);
        array_unique($genders);
         */

        return $gender;
    }
}