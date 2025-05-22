<?php

namespace App\Tests\Form;

use Symfony\Component\Form\Test\TypeTestCase;
use App\Form\PaymentMockType;
use Symfony\Component\Form\PreloadedExtension;

class PaymentMockTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        return []; // Aucun champ personnalisé à injecter ici
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'card_number' => '4242424242424242',
            'expiration' => date('m') . ' / ' . date('y', strtotime('+1 year')),
            'cvc' => '123',
            'cardholder' => 'Jean Dupont',
            'country' => 'France',
        ];

        $form = $this->factory->create(PaymentMockType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
    }

    public function testSubmitInvalidCardNumber(): void
    {
        $formData = [
            'card_number' => '12345678',
            'expiration' => '12 / 30',
            'cvc' => '123',
            'cardholder' => 'Jean Dupont',
            'country' => 'France',
        ];

        $form = $this->factory->create(PaymentMockType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
    }

    public function testSubmitExpiredCard(): void
    {
        $formData = [
            'card_number' => '4242424242424242',
            'expiration' => '01 / 20', // Date expirée
            'cvc' => '123',
            'cardholder' => 'Jean Dupont',
            'country' => 'France',
        ];

        $form = $this->factory->create(PaymentMockType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
    }

    public function testSubmitInvalidCVC(): void
    {
        $formData = [
            'card_number' => '4242424242424242',
            'expiration' => '12 / 30',
            'cvc' => '12a', // Invalide
            'cardholder' => 'Jean Dupont',
            'country' => 'France',
        ];

        $form = $this->factory->create(PaymentMockType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
    }

    public function testSubmitInvalidCardholder(): void
    {
        $formData = [
            'card_number' => '4242424242424242',
            'expiration' => '12 / 30',
            'cvc' => '123',
            'cardholder' => 'Jean@123',
            'country' => 'France',
        ];

        $form = $this->factory->create(PaymentMockType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
    }
}
