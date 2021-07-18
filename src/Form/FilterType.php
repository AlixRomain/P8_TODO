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
                    'Deadline' => 0,
                    'Toutes'=> 'all',
                    'Terminé' => 2,
                    'Encours' => 3,
                ],
                'label' => "Filtres de tâches",
                'attr' => [
                    'placeholder' => 'Filtré les tâches',
                    'class' =>'form-control d-inline ',
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
