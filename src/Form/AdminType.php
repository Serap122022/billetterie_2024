<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('firstName', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'multiple' => true, // Permet de sélectionner plusieurs rôles
                'expanded' => true, // Affiche les choix sous forme de cases à cocher
                'label' => 'Choisir les rôles',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}