<?php

namespace App\Tests\Repository;

use App\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AdminRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $adminRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$container->get('doctrine')->getManager();
        $this->adminRepository = $this->entityManager->getRepository(Admin::class);
    }

    public function testFindAndFindAll(): void
    {
        // Création d'un Admin
        $admin = new Admin();
        $admin->setEmail('ad.jo.2024@outlook.com');
        $admin->setUsername('david');
        $admin->setFirstName('dylan');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword('dummy'); 

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        // Test find
        $foundAdmin = $this->adminRepository->find($admin->getId());
        $this->assertNotNull($foundAdmin);
        $this->assertSame('ad.jo.2024@outlook.com', $foundAdmin->getEmail());

        // Test findAll contient au moins cet admin
        $allAdmins = $this->adminRepository->findAll();
        $this->assertContains($foundAdmin, $allAdmins);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; 
    }
}
