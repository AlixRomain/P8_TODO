<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filterUser', ChoiceType::class, [
                'choices'  => [
                    'Role Admin' => 0,
                    'Role User' => 1,
                    'Tous les utilisateurs' => 'all'
                ],
                'choice_attr' =>[
                    'Role Admin' => ['class' => 'optio'],
                    'Role User' => ['class' => 'optio'],
                    'Tous les utilisateurs' => ['class' => 'optio'],
                ],
                'label' => "Filtres d'utilisateur",
                'attr' => [
                    'placeholder' => 'Filtré les utilisateurs',
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
