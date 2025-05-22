<?php

namespace App\Tests\Entity;

use App\Entity\Evenements;
use PHPUnit\Framework\TestCase;

class EvenementsTest extends TestCase
{
    public function testSettersAndGetters()
    {
        $evenement = new Evenements();
        $date = new \DateTime('2025-05-01');

        $evenement->setNomEvenement('Concert')
                  ->setDateEvenement($date)
                  ->setDescription('Un concert incroyable.')
                  ->setUrlImage('http://example.com/image.jpg')
                  ->setLogo('http://example.com/logo.jpg');

        // Vérifiez les valeurs après les modifications
        $this->assertEquals('Concert', $evenement->getNomEvenement());
        $this->assertEquals($date, $evenement->getDateEvenement());
        $this->assertEquals('Un concert incroyable.', $evenement->getDescription());
        $this->assertEquals('http://example.com/image.jpg', $evenement->getUrlImage());
        $this->assertEquals('http://example.com/logo.jpg', $evenement->getLogo());
    }

    public function testDisplayDetails()
    {
        $evenement = new Evenements();
        $date = new \DateTime('2025-05-01');
        $evenement->setNomEvenement('Concert')->setDateEvenement($date);

        $this->assertEquals('Concert - 01/05/2025', $evenement->getDisplayDetails());
    }
}
