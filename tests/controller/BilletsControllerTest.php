<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\BilletsRepository;
use App\Entity\Billets;
use Doctrine\ORM\EntityManagerInterface;

class BilletsControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testIndex(): void
    {
        $this->client->request('GET', '/billets/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1'); // Vérifie qu'il y a un <h1> sur la page
    }

    public function testShowBillet(): void
    {
        $billet = new Billets();
        $billet->setNom('Test billet');
        $billet->setPrix(10);
        $billet->setDescription('Test description');

        $this->entityManager->persist($billet);
        $this->entityManager->flush();

        $this->client->request('GET', '/billets/' . $billet->getId());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', $billet->getNom());
    }

    public function testCreateBillet(): void
    {
        $crawler = $this->client->request('GET', '/billets/new');

        $form = $crawler->selectButton('Enregistrer')->form([
            'billets[nom]' => 'Nouveau billet',
            'billets[prix]' => 25,
            'billets[description]' => 'Description test',
        ]);

        $this->client->submit($form);

        $this->assertResponseRedirects();
        $this->client->followRedirect();

        $this->assertSelectorTextContains('h1', 'Liste des billets');
        $this->assertSelectorTextContains('td', 'Nouveau billet');
    }

    public function testEditBillet(): void
    {
        $billet = new Billets();
        $billet->setNom('À modifier');
        $billet->setPrix(15);
        $billet->setDescription('Ancienne description');

        $this->entityManager->persist($billet);
        $this->entityManager->flush();

        $crawler = $this->client->request('GET', '/billets/' . $billet->getId() . '/edit');

        $form = $crawler->selectButton('Mettre à jour')->form([
            'billets[nom]' => 'Modifié',
            'billets[prix]' => 20,
            'billets[description]' => 'Nouvelle description',
        ]);

        $this->client->submit($form);
        $this->assertResponseRedirects();
        $this->client->followRedirect();

        $this->assertSelectorTextContains('h1', 'Modifié');
    }

    public function testDeleteBillet(): void
    {
        $billet = new Billets();
        $billet->setNom('À supprimer');
        $billet->setPrix(30);
        $billet->setDescription('À supprimer desc');

        $this->entityManager->persist($billet);
        $this->entityManager->flush();

        $crawler = $this->client->request('GET', '/billets/');

        $deleteForm = $crawler->filter('form')->last()->form();
        $this->client->submit($deleteForm);

        $this->assertResponseRedirects('/billets/');
        $this->client->followRedirect();

        $this->assertSelectorNotExists('td:contains("À supprimer")');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->clear();
    }
}
