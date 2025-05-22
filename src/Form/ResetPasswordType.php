<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'attr' => ['placeholder' => 'Entrez votre nouveau mot de passe'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le mot de passe est obligatoire.']),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit contenir au moins 8 caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => "/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/",
                        'message' => "Le mot de passe doit contenir au moins une majuscule, un chiffre et un caractère spécial.",
                    ]),
                ],
            ])
            ->add('confirm_password', PasswordType::class, [
                'label' => 'Confirmez le mot de passe',
                'attr' => ['placeholder' => 'Confirmez votre mot de passe'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez confirmer votre mot de passe.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => new Assert\Callback([$this, 'validatePasswords']),
        ]);
    }

    public function validatePasswords(array $data, ExecutionContextInterface $context): void
    {
        if (!isset($data['password'], $data['confirm_password'])) {
            return;
        }

        if ($data['password'] !== $data['confirm_password']) {
            $context->buildViolation('Les mots de passe ne correspondent pas.')
                ->atPath('confirm_password')
                ->addViolation();
        }
    }
}
