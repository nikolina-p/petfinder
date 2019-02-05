<?php

namespace App\Entity;

use App\Entity\Photo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PetRepository")
 */
class Pet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"new", "edit"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(groups={"new", "edit"})
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(groups={"new", "edit"})
     */
    private $age;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="pet",
     *     cascade={"persist", "remove"}, orphanRemoval = true)
     * @Assert\Valid(traverse="true", groups={"new"})
     * @Assert\Count(
     *      min = "1",
     *      minMessage = "You must upload at least one image",
     *      groups={"new"}
     * )
     */
    private $photos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="pets")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(groups={"new", "edit"})
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Species", inversedBy="pets")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(groups={"new", "edit"})
     */
    private $species;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(groups={"new", "edit"})
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(groups={"new", "edit"})
     */
    private $breed;


    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getPhotos(): ?Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setPet($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            return $this;
        }
        $this->photos->removeElement($photo);
        // set the owning side to null (unless already changed)
        if ($photo->getPet() === $this) {
            $photo->setPet(null);
        }

        return $this;
    }

    public function setPhotos(ArrayCollection $photos): void
    {
        $this->photos = $photos;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function setSpecies(?Species $species): self
    {
        $this->species = $species;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBreed(): ?string
    {
        return $this->breed;
    }

    public function setBreed(string $breed): self
    {
        $this->breed = $breed;

        return $this;
    }
}
