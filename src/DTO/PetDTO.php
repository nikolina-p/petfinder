<?php

namespace App\DTO;

use App\Entity\Species;
use Symfony\Component\Validator\Constraints as Assert;

class PetDTO
{
    private $species;

    private $breed;

    /**
     *  @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    private $age;

    private $gender;

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function getSpeciesId(): ?int
    {
        if ($this->getSpecies() !== null) {
            return $this->species->getId();
        }

        return null;
    }

    public function getBreed(): ?string
    {
        return $this->breed;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setSpecies(Species $species): self
    {
        $this->species = $species;
        return $this;
    }

    public function setBreed(string $breed): self
    {
        $this->breed = $breed;
        return $this;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function hasSpecies(): bool
    {
        return $this->species !== null;
    }

    public function hasBreed(): bool
    {
        return $this->breed !== null;
    }

    public function hasAge(): bool
    {
        return $this->age !== null;
    }

    public function hasGender(): bool
    {
        return $this->gender !== null;
    }
}
