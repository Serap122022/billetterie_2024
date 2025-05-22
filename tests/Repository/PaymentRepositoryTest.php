<?php

namespace App\Tests\Repository;

use App\Entity\Payment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PaymentRepositoryTest extends KernelTestCase
{
    public function testCanPersistAndRetrievePayment(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $em = $container->get('doctrine')->getManager();

        $payment = new Payment();
        // Assure-toi de remplir tous les champs obligatoires
        $payment->setAmount(100);
        $payment->setMethod('fake');
        $payment->setStatus('completed');
        $payment->setCreatedAt(new \DateTimeImmutable());

        $em->persist($payment);
        $em->flush();
        $em->clear();

        $found = $em->getRepository(Payment::class)->find($payment->getId());

        $this->assertNotNull($found);
        $this->assertEquals(100, $found->getAmount());
    }
}
