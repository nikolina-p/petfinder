<?php

namespace App\Tests\Form;

use App\Entity\Pet;
use App\Entity\Photo;
use App\Entity\Species;
use App\Entity\User;
use App\Form\PetForm;
use App\Form\PhotoTransformer;
use App\Form\UserTransformer;
use App\Kernel;
use App\Service\UserService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PetFormTest extends TypeTestCase
{
    private $photoTransformer;
    private $userTransformer;
    private $security;
    /**
     * @var Kernel
     */
    protected static $kernel;
    protected static $container;

    protected function get(string $serviceId)
    {
        return $this->getContainer()->get($serviceId);
    }

    protected function getContainer()
    {
        if (!isset(self::$kernel)) {
            self::$kernel = new Kernel($_ENV['APP_ENV'], $_ENV['APP_DEBUG']);
            self::$kernel->boot();
        }

        if (!isset(self::$container)) {
            self::$container = self::$kernel->getContainer();
        }

        return self::$container;
    }

    public function testSubmitValidData()
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        $photo = new Photo();
        $uploadedFile = new UploadedFile('/home/nikolina/Downloads/Olu pogled.jpg', 'Olu pogled.jpg');
        $photo->setFile($uploadedFile);

        $formData = [
            'name' => "nik",
            'description' => 'opis',
            'age' => 12,
            'species' => (new Species())->setSpeciesName('pas'),
            'gender' => 'MALE',
            'breed' => 'neka vrsta',
            'photos' => new ArrayCollection([$photo]), //transformer
            'owner' => $user, //transformer
        ];

        $objectToCompare = new Pet();

        $form = $this->factory->create(PetForm::class, $objectToCompare);

        $object = new Pet();
        $object->setName('nik');
        $object->setDescription('opis');
        $object->setAge(12);
        $object->setSpecies((new Species())->setSpeciesName('pas'));
        $object->setGender('MALE');
        $object->setBreed('neka vrsta');
        $object->setPhotos(new ArrayCollection([$photo]));
        $object->setOwner($user);


        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    protected function setUp()
    {
        $this->userTransformer = $this->createMock(UserTransformer::class);
        $this->userTransformer->method('transform')->willReturn(rand(1,30));
        $this->userTransformer->method('reverseTransform')->willReturn(new User());

        $this->photoTransformer = new PhotoTransformer();

        $this->security = $this->createMock(Security::class);
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $this->security->method('getUser')->willReturn($user);

        parent::setUp();
    }

    protected function getExtensions()
    {
        $petForm = new PetForm($this->photoTransformer, $this->userTransformer, $this->security);

        return [
            new PreloadedExtension([$petForm, new EntityType($this->get('doctrine'))], [])
        ];
    }
}
