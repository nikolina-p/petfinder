<?php

namespace App\Form;

use App\Entity\Pet;
use App\Entity\Photo;
use App\Entity\Species;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('species', EntityType::class, [
                'class' => Species::class,
                'choice_label' => 'speciesName',
                'required' => false
            ])
            ->add('breed', TextType::class, [
                'label' => "Breed",
                'required' => false
            ])
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Male' => 'MALE',
                    'Female' => 'FEMALE',
                    'Unknown' => "UNKNOWN",
                ],
                'required' => false
            ])
            ->add('age', NumberType::class, [
                'label' => 'Age',
                'scale' => 2,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pet::class,
        ]);
    }
}