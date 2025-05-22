<?php

namespace App\Tests\Form;

use App\Entity\Orders;
use App\Form\OrdersType;
use Symfony\Component\Form\Test\TypeTestCase;

class OrdersTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $order = new Orders();
        $order->setOrderKey('ABC123XYZ');
        $order->setTotalPrice(99.99);
        $order->setOrderDate(new \DateTime('2024-05-01'));

        $formData = [
            'orderKey'   => 'ABC123XYZ',
            'orderDate'  => '2024-05-01',
            'totalPrice' => 99.99,
            'lastName'   => 'Durand',
            'firstName'  => 'Pierre',
            'address'    => '12 Rue Exemple',
            'postalCode' => '75001',
            'city'       => 'Paris',
            'country'    => 'France',
        ];

        $form = $this->factory->create(OrdersType::class, $order);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());

        $this->assertSame('Durand', $order->getLastName());
        $this->assertSame('Pierre', $order->getFirstName());
        $this->assertSame('12 Rue Exemple', $order->getAddress());
        $this->assertSame('75001', $order->getPostalCode());
        $this->assertSame('Paris', $order->getCity());
        $this->assertSame('France', $order->getCountry());
    }

    public function testInvalidPostalCode(): void
    {
        $order = new Orders();
        $order->setOrderKey('TEST123');
        $order->setTotalPrice(50.00);
        $order->setOrderDate(new \DateTime());

        $formData = [
            'orderKey'   => 'TEST123',
            'orderDate'  => '2024-05-01',
            'totalPrice' => 50.00,
            'lastName'   => 'Dupont',
            'firstName'  => 'Jean',
            'address'    => '1 Rue Test',
            'postalCode' => 'ABCDE', // invalide
            'city'       => 'Lyon',
            'country'    => 'France',
        ];

        $form = $this->factory->create(OrdersType::class, $order);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
    }

    public function testBlankFields(): void
    {
        $order = new Orders();
        $order->setOrderKey('TEST456');
        $order->setTotalPrice(45.00);
        $order->setOrderDate(new \DateTime());

        $formData = [
            'orderKey'   => 'TEST456',
            'orderDate'  => '2024-05-01',
            'totalPrice' => 45.00,
            'lastName'   => '',
            'firstName'  => '',
            'address'    => '',
            'postalCode' => '',
            'city'       => '',
            'country'    => '',
        ];

        $form = $this->factory->create(OrdersType::class, $order);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
    }
}
