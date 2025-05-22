<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccessDeniedControllerTest extends WebTestCase
{
    public function testAccessDeniedPageIsAccessible(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/access-denied');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Accès refusé'); // ou texte présent dans ta vue
    }
}
