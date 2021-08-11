<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => [
                    'placeholder' => 'toto@toto.com',
                    'class' =>'form-control',
                ]
            ])
            ->add('name', TextType::class, [
                'label' => "Nom d'utilisateur",
                'attr' => [
                    'placeholder' => 'Le nom de l\'utilisateur',
                    'class' =>'form-control',
                ]
            ])
             ->add('role', ChoiceType::class, [
                 'required'=>true,
                 'mapped' => false,
                 'choices'  => [
                     'Administrateur' => 'ROLE_ADMIN',
                     'Utilisateur' => 'ROLE_USER'
                 ],
                 'choice_attr' =>[
                     'Role Admin' => ['class' => 'optio'],
                     'Role User' => ['class' => 'optio'],
                 ],
                 'label' => "Rôle utilisateur",
                 'attr' => [
                     'placeholder' => 'Choisissez un rôle utilisateur',
                     'class' =>'form-control d-inline ',
                 ]
             ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                'required' => true,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Votre mot de passe',
                        'class' =>'form-control mb-3',
                    ]
                ],
                'second_options' => [
                    'label' => 'Tapez le mot de passe à nouveau',
                    'attr' => [
                        'placeholder' => 'Saisissez à nouveau votre mot de passe',
                        'class' =>'form-control',
                    ]
                ],

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
