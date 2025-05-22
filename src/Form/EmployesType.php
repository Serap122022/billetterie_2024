<?php

namespace App\Form;

use App\Entity\Employes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EmployesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est obligatoire.']),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('prenom', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prénom est obligatoire.']),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L’adresse email est obligatoire.']),
                    new Assert\Email(['message' => 'L’adresse email n’est pas valide.']),
                ],
            ])
            ->add('password', PasswordType::class, [
                'required' => !$options['edit_mode'], // Requis si en mode création
                'mapped' => true, 
                'constraints' => $options['edit_mode'] ? [] : [
                    new NotBlank(['message' => 'Le mot de passe ne peut pas être vide.']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 50,
                    ]),
                ],
                'empty_data' => '', // Pour éviter les erreurs si le champ est vide
            ])

          
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'Employé' => 'ROLE_EMPLOYE',
                ],
                'mapped' => false,     
                'expanded' => false,      // menu déroulant (select), sinon checkbox
                'disabled' => true,       // champ affiché mais non modifiable
                'label' => 'Rôle',
            ])

    ->add('isActive', ChoiceType::class, [
        'label' => 'Statut',
        'choices'  => [
            'Actif' => true,
            'Inactif' => false,
        ],
        'expanded' => true, // Affiche comme des radio buttons
        'multiple' => false, // Une seule option sélectionnable
        'data' => true, // Valeur par défaut (optionnel si tu l’as déjà dans l’entité)
    ]);
}

public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employes::class,
            'edit_mode' => false,
        ]);
    }
}
