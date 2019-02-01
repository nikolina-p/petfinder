<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpeciesRepository")
 */
class Species
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $speciesName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpeciesName(): ?string
    {
        return $this->speciesName;
    }

    public function setSpeciesName(string $speciesName): self
    {
        $this->speciesName = $speciesName;

        return $this;
    }
}
