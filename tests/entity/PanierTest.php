<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Panier;
use App\Entity\Billets;
use App\Entity\User;

class PanierTest extends TestCase
{
    public function testInitialValues()
    {
        $panier = new Panier();
        $this->assertNull($panier->getId());
        $this->assertNull($panier->getBillet());
        $this->assertNull($panier->getUser ());
        $this->assertEquals(0, $panier->getQuantite());
        $this->assertEquals(0.0, $panier->getMontant());
        $this->assertInstanceOf(\DateTimeInterface::class, $panier->getCreatedAt());
    }

    public function testSetBillet()
{
    $panier = new Panier();
    $billet = new Billets();
    $billet->setType('solo');
    $billet->setTarif(40.00); // Assurez-vous que le tarif est défini
    $panier->setBillet($billet);
    $this->assertEquals($billet, $panier->getBillet());
}

    public function testSetQuantite()
    {
        $panier = new Panier();
        $panier->setQuantite(3);
        $this->assertEquals(3, $panier->getQuantite());
    }

    public function testSetUser ()
    {
        $panier = new Panier();
        $user = new User();
        $panier->setUser ($user);
        $this->assertEquals($user, $panier->getUser ());
    }

    public function testGetMontantWithTarifs()
    {
        $panier = new Panier();
        $billet = new Billets();
    
        // Test pour le tarif solo
        $billet->setType('solo');
        $billet->setTarif(40.00);
        $panier->setBillet($billet);
        $panier->setQuantite(3);
        $this->assertEquals(120.00, $panier->getMontant());
    
        // Test pour le tarif duo
        $billet->setType('duo');
        $billet->setTarif(80.00);
        $panier->setQuantite(2);
        $this->assertEquals(160.00, $panier->getMontant());
    
        // Test pour le tarif family
        $billet->setType('family');
        $billet->setTarif(120.00);
        $panier->setQuantite(1);
        $this->assertEquals(120.00, $panier->getMontant());
    }
    

    public function testSetMontant()
    {
        $panier = new Panier();
        $panier->setMontant(200.00);
        $this->assertEquals(200.00, $panier->getMontant());
    }

    public function testGetTotalBillets()
    {
        $panier = new Panier();
        $items = [
            ['quantity' => 2],
            ['quantity' => 3],
            ['quantity' => 1],
        ];

        $total = $panier->getTotalBillets($items);
        $this->assertEquals(6, $total);
    }
}