<?php

namespace App\Form;

use App\Entity\Pet;
use App\Form\PhotoTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PetForm extends AbstractType
{
    private $photoTransformer;

    public function __construct(PhotoTransformer $photoTransformer)
    {
        $this->photoTransformer = $photoTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Pet name'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('age', NumberType::class, [
                'label' => 'How old is the pet?',
                'scale' => 2
            ])
            ->add('photos', FileType::class, ['multiple' => true]);

        $builder->get('photos')->addModelTransformer($this->photoTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pet::class
        ]);
    }
}