<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filter', ChoiceType::class, [
                'choices'  => [
                    'Ranger par Deadline' => 0,
                    'Toutes'=> 'all',
                    'Terminé' => 2,
                    'Encours' => 3,
                    'Qui me sont destinés' => 4

                ],
                'choice_attr' =>[
                    'Ranger par Deadline' => ['class' => 'optio'],
                    'Toutes' => ['class' => 'optio'],
                    'Terminé' => ['class' => 'optio'],
                    'Encours' => ['class' => 'optio'],
                    'Qui me sont destinés' => ['class' => 'optio'],
                ],
                'label' => "Filtres de tâches",
                'attr' => [
                    'placeholder' => 'Filtré les tâches',
                    'class' =>'form-control d-inline ',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
