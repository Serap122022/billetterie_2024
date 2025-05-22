<?php

namespace App\Tests\Repository;

use App\Entity\Employes;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EmployesRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$container->get('doctrine')->getManager();
        $this->repository = $this->entityManager->getRepository(Employes::class);
    }

    public function testFindAllReturnsArrayOfEmployes()
    {
        $employes = $this->repository->findAll();
        $this->assertIsArray($employes);
        foreach ($employes as $employe) {
            $this->assertInstanceOf(Employes::class, $employe);
        }
    }

    public function testFindReturnsEmployeOrNull()
    {
        $employe = $this->repository->find(1);
        $this->assertTrue($employe === null || $employe instanceof Employes);
    }

    public function testFindOneByReturnsEmployeOrNull()
    {
        $criteria = ['id' => 1];
        $employe = $this->repository->findOneBy($criteria);
        $this->assertTrue($employe === null || $employe instanceof Employes);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
