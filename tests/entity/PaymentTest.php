<?php

namespace App\Tests\Entity;

use App\Entity\Payment;
use App\Entity\User;
use App\Entity\Billets;
use App\Enum\PaymentStatusEnum;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    public function testPaymentEntity()
    {
        $payment = new Payment();

        // Test des valeurs initialisées dans le constructeur
        $this->assertNotNull($payment->getClePaiement(), 'Clé de paiement générée automatiquement');
        $this->assertInstanceOf(\DateTimeImmutable::class, $payment->getDateCreation());

        // Setters
        $payment->setMontant(99.99);
        $payment->setMethodePaiement('carte bancaire');
        $payment->setStatutPaiement(PaymentStatusEnum::SUCCES); // Enum attendu

        $this->assertEquals(99.99, $payment->getMontant());
        $this->assertEquals('carte bancaire', $payment->getMethodePaiement());
        $this->assertEquals(PaymentStatusEnum::SUCCES, $payment->getStatutPaiement());

        // Relations avec User et Billets
        $user = new User();
        $billet = new Billets();

        $payment->setUtilisateur($user);
        $payment->setBillet($billet);

        $this->assertSame($user, $payment->getUtilisateur());
        $this->assertSame($billet, $payment->getBillet());
    }
}
