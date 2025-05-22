<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private $entityManager;
    private $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$container->get('doctrine')->getManager();
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function testFindAllReturnsArrayOfUsers()
    {
        $users = $this->repository->findAll();
        $this->assertIsArray($users);
        foreach ($users as $user) {
            $this->assertInstanceOf(User::class, $user);
        }
    }

    public function testFindReturnsUserOrNull()
    {
        $user = $this->repository->find(1);
        $this->assertTrue($user === null || $user instanceof User);
    }

    public function testFindOneByReturnsUserOrNull()
    {
        $criteria = ['id' => 1];
        $user = $this->repository->findOneBy($criteria);
        $this->assertTrue($user === null || $user instanceof User);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
