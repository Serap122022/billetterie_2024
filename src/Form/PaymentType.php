<?php

namespace App\Form;

use App\Entity\Payment; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DecimalType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Order;
use App\Enum\PaymentStatusEnum;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('order', EntityType::class, [
                'class' => Order::class,
                'choice_label' => 'id', 
                'label' => 'Commande',
            ])
            ->add('amount', DecimalType::class, [
                'label' => 'Montant',
                'scale' => 2,
            ])
            ->add('payment_status', ChoiceType::class, [
                'label' => 'Statut du paiement',
                'choices' => PaymentStatusEnum::getChoices(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
