<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choice = [];
        $choiceClass=[];
     /*   array_push($choice,[
            'Ouverte à tous' =>  'default'
        ]);*/
        foreach ($options['users'] as $val){
            array_push($choice,[ $val->getName() =>  $val]);
            array_push($choiceClass, [$val->getName() => ['class' => 'optio']]);
        };

        $builder
            ->add('title', TextType::class, [
                'label' => "Titre",
                'attr' => [
                    'placeholder' => 'Votre titre',
                    'class' =>'form-control mb-3',
                ]
            ])
            ->add('deadline', DateType::class, [
                'widget' => 'choice',
                'attr' => [
                    'placeholder' => 'Votre titre',
                    'class' =>'form-control mb-3',
                ]
            ])
           ->add('content', TextType::class, [
                'label' => "Contenus",
                'attr' => [
                    'placeholder' => 'Votre contenu',
                    'class' =>'form-control mb-3',
                ]
            ])
            ->add('targetUser', ChoiceType::class, [
                'required'   => false,
                'empty_data' => 'default',
                'choices'  => $choice,
                'label' => "A qui voulez-vous assigner la tâche?",
                'attr' => [
                    'placeholder' => 'Filtré les tâches',
                    'class' =>'form-control d-inline mb-3',
                ],
                'choice_attr' => $choiceClass,
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
        $resolver->setRequired([
                                   'users'
                               ]);
    }
}
