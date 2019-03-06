<?php

namespace App\Tests\Form;

use App\Entity\Pet;
use App\Entity\Species;
use App\Form\PetForm;
use App\Form\PhotoTransformer;
use App\Form\UserTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PetFormTest extends TypeTestCase
{
    private $photoTransformer;
    private $userTransformer;
    private $security;
    private $containerInterface;

    public function __construct(ContainerInterface $containerInterface)
    {
        parent::__construct("PetFormTesting", [], '');
        $this->containerInterface = $containerInterface;
    }

    public function testSubmitValidData()
    {
        $formData = [
            'name' => "niki",
            'description' => 'opis',
            'age' => 12,
            'species' => new Species(),
            'gender' => 'MALE',
            'breed' => 'neka vrsta',
            'photos' => new FileType()
        ];

        $objectToCompare = new Pet();

        $form = $this->factory->create(PetForm::class, $objectToCompare);

        $object = new Pet();

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
        $this->photoTransformer = $this->createMock(PhotoTransformer::class);
        $this->userTransformer = $this->createMock(UserTransformer::class);
        $this->security = new Security($this->containerInterface );

        parent::setUp();
    }

    protected function getExtensions()
    {
        $petForm = new PetForm($this->photoTransformer, $this->userTransformer, $this->security);

        return [
            new PreloadedExtension([$petForm], [])
        ];
    }
}
