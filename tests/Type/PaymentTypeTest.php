<?php

namespace App\Tests\Form;

use App\Entity\Payment;
use App\Entity\Order;
use App\Enum\PaymentStatusEnum;
use App\Form\PaymentType;
use Symfony\Component\Form\Test\TypeTestCase;

class PaymentTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        // Création d'une instance d'entité Order simulée
        $order = new Order();
        $order->setId(123); // suppose que setId est possible (sinon mock)

        // Données simulées pour le formulaire
        $formData = [
            'order' => $order,
            'amount' => 150.25,
            'payment_status' => PaymentStatusEnum::PAID,
        ];

        // Création de l'objet Payment attendu après soumission
        $payment = new Payment();
        $payment->setOrder($order);
        $payment->setAmount(150.25);
        $payment->setPaymentStatus(PaymentStatusEnum::PAID);

        // Création du formulaire
        $form = $this->factory->create(PaymentType::class, new Payment());

        // Soumission des données simulées
        $form->submit([
            'order' => 123, // Ici on soumet l'id de l'entité Order
            'amount' => '150.25',
            'payment_status' => PaymentStatusEnum::PAID->value,
        ]);

        // Vérifications
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($payment->getAmount(), $form->getData()->getAmount());
        $this->assertEquals($payment->getPaymentStatus(), $form->getData()->getPaymentStatus());
        // L'entité order peut nécessiter un mock ou vérification spécifique selon ton setup
    }

    public function testFormFields()
    {
        $form = $this->factory->create(PaymentType::class);

        $this->assertTrue($form->has('order'));
        $this->assertTrue($form->has('amount'));
        $this->assertTrue($form->has('payment_status'));
    }
}
