<?php

namespace App\Form;

use App\Entity\Billets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class BilletsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', ChoiceType::class, [
            'choices' => [
                'Solo (1 personne)' => 'solo (1 personne)',
                'Duo (2 personnes)' => 'duo (2 personnes)',
                'Family (4 personnes)' => 'family (4 personnes)',
                'Autres types' => 'other',
            ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('tarif', IntegerType::class, [
                'attr' => ['id' => 'tarifField'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d+$/', // Regex pour autoriser uniquement les chiffres
                        'message' => 'Veuillez utiliser uniquement des chiffres.',
                    ]),
                ],
            ])
            ->add('customType', TextType::class, [
                'required' => false,
                'label' => 'Entrez le type personnalisé :',
                'attr' => ['id' => 'inputField'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9\s]*$/',
                        'message' => 'Veuillez utiliser uniquement des lettres et des chiffres, sans caractères spéciaux.',
                    ]),
                ],
          ])
          ->add('stock', IntegerType::class, [
            'label' => 'Quantité en stock :',
            'attr' => ['id' => 'stockField'],
            'constraints' => [
                new Regex([
                    'pattern' => '/^\d+$/', 
                    'message' => 'Veuillez entrer uniquement des chiffres.',
                ]), 
            ],
        ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Billets::class, 
        ]);
    }
}
