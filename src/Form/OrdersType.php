<?php

namespace App\Form;

use App\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Intl\NumberFormatter\NumberFormatter;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Constraints as Assert;

class OrdersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('orderKey', TextType::class, [
                'mapped' => false, // Ne pas mapper ce champ à l'entité
                'data' => $options['data']->getOrderKey(), // Utilise l'option pour obtenir la valeur par défaut
                'attr' => ['readonly' => true], // Rend le champ en lecture seule
            ])
            ->add('orderDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false, // Rend le champ requis
                'data' => $options['data']->getOrderDate() ?? new \DateTime(), // Utilise la valeur par défaut
                'attr' => ['readonly' => true], // Rend le champ en lecture seule
            ])

            ->add('totalPrice', HiddenType::class, [ // Utilisation d'un champ caché
                'data' => $options['data']->getTotalPrice(), // Utilise la valeur de l'entité
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom ne peut pas être vide.']),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÿ]+$/', // Autorise uniquement lettres
                        'message' => 'Le nom d\'utilisateur ne peut contenir que des lettres.',
                    ]),
                ],
            ])
            
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prénom ne peut pas être vide.']),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÿ]+$/', 
                        'message' => 'Le prénom d\'utilisateur ne peut contenir que des lettres.',
                    ]),
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'adresse ne peut pas être vide.']),
                    new Length(['max' => 255, 'maxMessage' => 'L\'adresse ne peut pas dépasser 255 caractères.']),
                    new Regex([
                        'pattern' => '/^[A-Za-z0-9À-ÿ\s]+$/',
                        'message' => 'L\'adresse ne peut contenir que des lettres, des chiffres et des espaces.',
                    ]),
                ],
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le code postal ne peut pas être vide.']),
                    new Regex([
                        'pattern' => '/^\d{5}$/',
                        'message' => 'Le code postal doit être composé de 5 chiffres.',
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La ville ne peut pas être vide.']),
                    new Length(['max' => 100, 'maxMessage' => 'Le nom de la ville ne peut pas dépasser 100 caractères.']),
                ],
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le pays ne peut pas être vide.']),
                    new Length(['max' => 100, 'maxMessage' => 'Le pays ne peut pas dépasser 100 caractères.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}