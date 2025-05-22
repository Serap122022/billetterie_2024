<?php

namespace App\Tests\Controller;

use App\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminControllerTest extends WebTestCase
{
    private function createAdmin(): Admin
    {
        $em = static::getContainer()->get('doctrine')->getManager();
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $admin = new Admin();
        $admin->setEmail('ad.jo.2024@outlook.com');
        $admin->setUsername('david');
        $admin->setFirstName('dylan');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($hasher->hashPassword($admin, 'Jo2024*@'));

        $em->persist($admin);
        $em->flush();
        $em->clear();

        return $em->getRepository(Admin::class)->findOneBy(['email' => 'ad.jo.2024@outlook.com']);
    }

    public function testAdminAccessAdminDashboard(): void
    {
        $client = static::createClient();
        $admin = $this->createAdmin();

        $client->loginUser($admin); // simulate login as Admin
        $client->request('GET', '/admin');

        $this->assertResponseIsSuccessful(); // 200 OK
        $this->assertSelectorTextContains('h1', 'Tableau de bord'); 
    }
}
