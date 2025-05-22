<?php

namespace App\Tests\Controller;

use App\Controller\ConditionsController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConditionsControllerTest extends WebTestCase
{
    public function testConditionsGeneralesVente()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $this->generateUrl('conditions_generales_vente'));

        $this->assertResponseIsSuccessful();
        $this->assertSame('cgv.html.twig', $crawler->getResponse()->getTargetTemplate());
    }

    // Ajoutez des tests similaires pour les autres méthodes du ConditionsController
}
