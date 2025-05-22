<?php

namespace App\Tests\Entity;

use App\Entity\TotalBillets;
use PHPUnit\Framework\TestCase;

class TotalBilletsTest extends TestCase
{
    public function testInitialValues()
    {
        $totalBillets = new TotalBillets();

        // Vérifiez les valeurs initiales
        $this->assertEquals(3000000, $totalBillets->getSolo());
        $this->assertEquals(3000000, $totalBillets->getDuo());
        $this->assertEquals(4000000, $totalBillets->getFamily());
        $this->assertEquals(0, $totalBillets->getVendus());
        $this->assertEquals(0.0, $totalBillets->getPrixTotalRecuperes());
    }

    public function testSettersAndGetters()
    {
        $totalBillets = new TotalBillets();

        // Test des setters
        $totalBillets->setSolo(2500000);
        $totalBillets->setDuo(2500000);
        $totalBillets->setFamily(3500000);
        $totalBillets->setVendus(1000);
        $totalBillets->setPrixTotalRecuperes(50000.0);

        // Vérifiez les valeurs après les modifications
        $this->assertEquals(2500000, $totalBillets->getSolo());
        $this->assertEquals(2500000, $totalBillets->getDuo());
        $this->assertEquals(3500000, $totalBillets->getFamily());
        $this->assertEquals(1000, $totalBillets->getVendus());
        $this->assertEquals(50000.0, $totalBillets->getPrixTotalRecuperes());
    }
}
