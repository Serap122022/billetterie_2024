<?php

namespace App\Tests\Controller;

use App\Entity\Admin;
use App\Entity\Employes;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminEmployesCrudTest extends WebTestCase
{
    private function createAdmin(): Admin
    {
        $em = static::getContainer()->get('doctrine')->getManager();
        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $admin = new Admin();
         $admin->setEmail('ad.jo.2024@outlook.com');
        $admin->setUsername('david');
        $admin->setFirstName('dylan');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($hasher->hashPassword($admin, 'Jo2024*@'));


        $em->persist($admin);
        $em->flush();

        return $admin;
    }

    public function testCreateEmploye(): void
    {
        $client = static::createClient();
        $admin = $this->createAdmin();
        $client->loginUser($admin);

        $crawler = $client->request('GET', '/admin/employes/new');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Enregistrer')->form([
            'employes[nom]' => 'TestNom',
            'employes[prenom]' => 'TestPrenom',
            'employes[email]' => 'testemploye@example.com',
            'employes[password][first]' => 'StrongPwd123!',
            'employes[password][second]' => 'StrongPwd123!',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/admin/employes');
    }

    public function testEditEmploye(): void
    {
        $client = static::createClient();
        $admin = $this->createAdmin();
        $client->loginUser($admin);

        $em = static::getContainer()->get('doctrine')->getManager();
        $employe = new Employes();
        $employe->setNom('Modif')->setPrenom('Prenom')->setEmail('modif@example.com')->setPassword('dummy');
        $em->persist($employe);
        $em->flush();

        $crawler = $client->request('GET', '/admin/employes/' . $employe->getId() . '/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Mettre à jour')->form([
            'employes[nom]' => 'Modifié',
            'employes[prenom]' => 'MisAJour',
            'employes[email]' => 'modif@example.com',
            'employes[password][first]' => '',
            'employes[password][second]' => '',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('/admin/employes');
    }

    public function testDeleteEmploye(): void
    {
        $client = static::createClient();
        $admin = $this->createAdmin();
        $client->loginUser($admin);

        $em = static::getContainer()->get('doctrine')->getManager();
        $employe = new Employes();
        $employe->setNom('ToDelete')->setPrenom('Temp')->setEmail('delete@example.com')->setPassword('dummy');
        $em->persist($employe);
        $em->flush();

        // Récupérer la page index pour trouver le token CSRF
        $crawler = $client->request('GET', '/admin/employes');
        $this->assertResponseIsSuccessful();

        // Crée une fausse requête de suppression avec un token CSRF
        $csrfToken = static::getContainer()->get('security.csrf.token_manager')->getToken('delete' . $employe->getId());

        $client->request('POST', '/admin/employes/' . $employe->getId(), [
            '_token' => $csrfToken,
        ]);

        $this->assertResponseRedirects('/admin/employes');
    }
}
