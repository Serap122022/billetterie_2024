<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Billets;
use App\Entity\Payment;
use App\Entity\Orders;
use App\Entity\OrdersItem;
use App\Entity\User;

class BilletsTest extends TestCase
{
    public function testInitialValues()
    {
        $billet = new Billets();
        $this->assertNull($billet->getId());
        $this->assertNull($billet->getType());
        $this->assertNull($billet->getCustomType());
        $this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $billet->getPaiements());
        $this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $billet->getOrdersItems());
    }

    public function testSetType()
    {
        $billet = new Billets();
        $billet->setType('vip');
        $this->assertEquals('vip', $billet->getType());
    }

    public function testSetTarif()
    {
        $billet = new Billets();
        $billet->setTarif(59.99);
        $this->assertEquals(59.99, $billet->getTarif());
    }

    public function testDisplayTypeWithStandard()
    {
        $billet = new Billets();
        $billet->setType('standard');
        $this->assertEquals('standard', $billet->getDisplayType());
    }

    public function testDisplayTypeWithCustom()
    {
        $billet = new Billets();
        $billet->setType('other');
        $billet->setCustomType('accès anticipé');
        $this->assertEquals('accès anticipé', $billet->getDisplayType());
    }

    public function testAddAndRemovePaiement()
    {
        $billet = new Billets();
        $paiement = new Payment(); // Assurez-vous que Payment est une entité valide
        $billet->addPaiement($paiement);
        $this->assertTrue($billet->getPaiements()->contains($paiement));

        $billet->removePaiement($paiement);
        $this->assertFalse($billet->getPaiements()->contains($paiement));
    }

    public function testAddAndRemoveOrdersItem(): void
    {
        // Création d'un utilisateur fictif
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('password123');

        // Création d'une commande fictive
        $order = new Orders();
        $order->setCreatedAt(new \DateTimeImmutable());

        // Création d'un OrdersItem en passant les deux objets requis
        $ordersItem = new OrdersItem($user, $order);

        // Ajout de l'OrdersItem à l'utilisateur
        $user->addOrdersItem($ordersItem); // Utilisez la méthode d'ajout
        $this->assertTrue($user->getOrdersItems()->contains($ordersItem)); // Vérifiez que l'élément a été ajouté

        // Suppression de l'OrdersItem
        $user->removeOrdersItem($ordersItem); // Utilisez la méthode de suppression
        $this->assertFalse($user->getOrdersItems()->contains($ordersItem)); // Vérifiez que l'élément a été supprimé
    }
}