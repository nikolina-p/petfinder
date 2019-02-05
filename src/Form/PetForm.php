<?php

namespace App\Form;

use App\Entity\Pet;
use App\Entity\Species;
use App\Form\PhotoTransformer;
use App\Form\UserTransformer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PetForm extends AbstractType
{
    private $photoTransformer;
    private $userTransformer;
    private $security;

    public function __construct(
        PhotoTransformer $photoTransformer,
        UserTransformer $userTransformer,
        Security $security
    ) {
        $this->photoTransformer = $photoTransformer;
        $this->userTransformer = $userTransformer;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Pet name'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('age', NumberType::class, [
                'label' => 'How old is the pet?',
                'scale' => 2
            ])
            ->add('species', EntityType::class, [
                'class' => Species::class,
                'choice_label' => 'speciesName',
            ])
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Male' => 'MALE',
                    'Female' => 'FEMALE',
                    'Unknown' => "UNKNOWN",
                    ]
            ])
            ->add('breed', TextType::class, ['label' => 'Breed'])
            ->add('photos', FileType::class, ['multiple' => true, 'required' => false]);

        if ($this->security->getUser()->hasRole("ROLE_ADMIN")) {
            $builder->add('owner', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ]);
        } else {
            $builder->add('owner', HiddenType::class, [
                'data' => $this->security->getUser()->getId(),
            ]);
            $builder->get('owner')->addModelTransformer($this->userTransformer);
        }

        $builder->get('photos')->addModelTransformer($this->photoTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pet::class,
            'validation_groups' => ['Default', 'new']
        ]);
    }
}
