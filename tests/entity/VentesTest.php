<?php

namespace App\Tests\Entity;

use App\Entity\Ventes;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;

class VentesTest extends TestCase
{
    public function testInitialValues()
    {
        $ventes = new Ventes();

        // Vérifiez les valeurs initiales
        $this->assertEquals(4000000, $ventes->getSolo());
        $this->assertEquals(3000000, $ventes->getDuo());
        $this->assertEquals(3000000, $ventes->getFamily());
        $this->assertEquals(0, $ventes->getSoloVendus());
        $this->assertEquals(0, $ventes->getDuoVendus());
        $this->assertEquals(0, $ventes->getFamilyVendus());
    }

    public function testVenteBillet()
    {
        $ventes = new Ventes();
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $ventes->vendreBillet('Solo', 100, 40, $entityManager);
        $this->assertEquals(100, $ventes->getSoloVendus());
        $this->assertEquals(3900000, $ventes->getSoloReste());
        $this->assertEquals(4000, $ventes->getPrixTotalRecuperes()); // 100 * 40
    }

    public function testVenteBilletInsuffisant()
    {
        $ventes = new Ventes();
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Stock insuffisant pour Solo.");
        $ventes->vendreBillet('Solo', 5000000, 40, $this->createMock(EntityManagerInterface::class));
    }
}
