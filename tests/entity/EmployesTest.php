<?php

namespace App\Tests\Entity;

use App\Entity\Employes;
use PHPUnit\Framework\TestCase;

class EmployesTest extends TestCase
{
    public function testInitialValues()
    {
        $employe = new Employes();

        // Vérifiez les valeurs initiales
        $this->assertNotNull($employe->getCreatedAt());
        $this->assertEquals(['ROLE_EMPLOYE'], $employe->getRoles());
        $this->assertTrue($employe->isActive());
    }

    public function testSettersAndGetters()
    {
        $employe = new Employes();
        $employe->setNom('Dupont')
                ->setPrenom('Jean')
                ->setEmail('jean.dupont@example.com')
                ->setPassword('password123');

        // Vérifiez les valeurs après les modifications
        $this->assertEquals('Dupont', $employe->getNom());
        $this->assertEquals('Jean', $employe->getPrenom());
        $this->assertEquals('jean.dupont@example.com', $employe->getEmail());
        $this->assertEquals('password123', $employe->getPassword());
    }
}
