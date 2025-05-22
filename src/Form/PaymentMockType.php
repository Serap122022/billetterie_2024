<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{EmailType, TextType, ChoiceType, SubmitType};
use Symfony\Component\Validator\Constraints\{NotBlank, Email, Length, Regex, Callback};
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PaymentMockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('card_number', TextType::class, [
                'label' => 'Numéro de carte',
                'attr' => ['placeholder' => '1234123412341234', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le numéro de carte.']),
                    new Regex([
                        'pattern' => '/^\d{16}$/',
                        'message' => 'Le numéro de carte doit contenir exactement 16 chiffres.'
                    ])
                ]
            ])
            ->add('expiration', TextType::class, [
                'label' => 'Date d\'expiration',
                'attr' => ['placeholder' => 'MM / AA', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir la date d\'expiration.']),
                    new Regex([
                        'pattern' => '/^(0[1-9]|1[0-2])\s?\/\s?([0-9]{2})$/',
                        'message' => 'Format attendu : MM / AA'
                    ]),
                    new Callback(function ($value, ExecutionContextInterface $context) {
                        $value = trim($value);
                        if (preg_match('/^(0[1-9]|1[0-2])\s?\/\s?([0-9]{2})$/', $value, $matches)) {
                            $month = (int) $matches[1];
                            $year = (int) $matches[2];
                            $currentYear = (int) date('Y');
                            $currentMonth = (int) date('n');
                            $yearFull = 2000 + $year;

                            if (
                                $yearFull < $currentYear ||
                                ($yearFull === $currentYear && $month < $currentMonth)
                            ) {
                                $context->buildViolation('Votre carte est expirée ou la date est passée.')
                                    ->atPath('expiration')
                                    ->addViolation();
                            }
                        } else {
                            $context->buildViolation('Format de la date invalide. Utilisez MM / AA.')
                                ->atPath('expiration')
                                ->addViolation();
                        }
                    })
                ]
            ])
            ->add('cvc', TextType::class, [
                'label' => 'CVC',
                'attr' => ['placeholder' => 'CVC', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le CVC.']),
                    new Regex([
                        'pattern' => '/^\d{3}$/',
                        'message' => 'Le CVC doit contenir 3 chiffres.'
                    ])
                ]
            ])
            ->add('cardholder', TextType::class, [
                'label' => 'Nom du titulaire de la carte',
                'attr' => ['placeholder' => 'Nom complet', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir le nom du titulaire.']),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ \'-]+$/u',
                        'message' => 'Le nom ne peut contenir que des lettres, espaces, apostrophes ou traits d\'union.'
                    ])
                ]
            ])
            ->add('country', ChoiceType::class, [
                'label' => 'Pays ou région',
                'choices' => [
                    'France' => 'France',
                    'Belgique' => 'Belgique',
                    'Suisse' => 'Suisse',
                    'Canada' => 'Canada'
                ],
                'attr' => ['class' => 'form-select'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner un pays.'])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Payer',
                'attr' => ['class' => 'btn btn-primary w-100']
            ]);

        // Event listener pour nettoyer le numéro de carte avant la validation
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if (isset($data['card_number'])) {
                // Supprime les espaces dans le numéro de carte
                $data['card_number'] = str_replace(' ', '', $data['card_number']);
                $event->setData($data);
            }
        });
    }
}
