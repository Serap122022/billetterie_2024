<?php

namespace App\Form;

use App\Entity\Panier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class PanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('quantite', IntegerType::class, [
            'label' => 'Quantité',
            'attr' => ['min' => 1, 'value' => 1], // Valeur par défaut à 1
        ])
        ->add('ajouter', SubmitType::class, [
            'label' => 'Ajouter au panier',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
