<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                                   'data_class' => User::class,
                                   'csrf_protection' => false,
                               ]);
    }
}
