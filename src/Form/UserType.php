<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le nom d\'utilisateur ne peut pas être vide.',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le nom d\'utilisateur doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom d\'utilisateur ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÿ0-9]+$/', // Autorise lettres et chiffres
                        'message' => 'Le nom d\'utilisateur ne peut contenir que des lettres et des chiffres.',
                    ]),
                ],
            ])

            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le prénom ne peut pas être vide.',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le prénom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÿ0-9]+$/', // Autorise lettres et chiffres
                        'message' => 'Le nom d\'utilisateur ne peut contenir que des lettres et des chiffres.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'L\'email ne peut pas être vide.',
                    ]),
                    new Assert\Email([
                        'message' => 'Le format de l\'email est invalide.',
                    ]),
                ],
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => ['class' => 'form-control'],
                    'constraints' => [
                        new Assert\Length([
                            'min' => 8,
                            'minMessage' => 'Le mot de passe doit comporter au moins {{ limit }} caractères.',
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmez le mot de passe',
                    'attr' => ['class' => 'form-control'],
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'mapped' => true,
            ])
        
            ->add('terms', CheckboxType::class, [
                'label' => 'J\'accepte les termes et conditions',
                'required' => true, // Rend le champ obligatoire
                'constraints' => [
                    new Assert\IsTrue([
                        'message' => 'Vous devez accepter les termes et conditions.',
                    ]),
                ],
            ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_login' => false, 
        ]);
    }
}
