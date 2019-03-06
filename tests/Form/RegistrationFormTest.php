<?php

namespace App\Tests\Form;


use App\Entity\User;
use App\Form\RegistrationForm;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

class RegistrationFormTest extends TypeTestCase
{
    private $validator;

    protected function getExtensions()
    {
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));
        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata(Form::class)));

        return [
            new ValidatorExtension($this->validator),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'nikolina@mail.com',
            'password' => [
                'first_options' => 'nnn111',
                'second_options' => 'nnn111'
            ],
            'termsAccepted' => true
        ];

        $objectToCompare = new User();

        $form = $this->factory->create(RegistrationForm::class, $objectToCompare);

        $user = new User();
        $user->setEmail($formData['email']);
        $user->setPassword($formData['password']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($user, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

}
