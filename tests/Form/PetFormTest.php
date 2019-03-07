<?php

namespace App\Tests\Form;

use App\Entity\Pet;
use App\Entity\Species;
use App\Entity\User;
use App\Form\PetForm;
use App\Form\PhotoTransformer;
use App\Form\UserTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Security\Core\Security;

class PetFormTest extends TypeTestCase
{
    private $photoTransformer;
    private $userTransformer;
    private $security;

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
        $this->security = $this->createMock(Security::class);

        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        $this->security->method('getUser')
            ->willReturn($user);

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
