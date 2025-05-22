<?php

namespace App\Tests\Repository;

use App\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PanierRepositoryTest extends KernelTestCase
{
    private $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Panier::class);
    }

    public function testGetTotalBillets(): void
    {
        $em = static::getContainer()->get('doctrine')->getManager();

        // Ajoute 2 éléments de panier
        $p1 = new Panier();
        $p1->setQuantite(3);
        $em->persist($p1);

        $p2 = new Panier();
        $p2->setQuantite(5);
        $em->persist($p2);

        $em->flush();
        $em->clear();

        $total = $this->repository->getTotalBillets();
        $this->assertEquals(8, $total);
    }

    public function testAddToPanierNew(): void
    {
        $panier = [];
        $this->repository->addToPanier($panier, 1, 2);
        $this->assertArrayHasKey(1, $panier);
        $this->assertEquals(2, $panier[1]['quantity']);
    }

    public function testAddToPanierExisting(): void
    {
        $panier = [1 => ['quantity' => 3]];
        $this->repository->addToPanier($panier, 1, 2);
        $this->assertEquals(5, $panier[1]['quantity']);
    }

    public function testRemoveFromPanier(): void
    {
        $panier = [1 => ['quantity' => 3], 2 => ['quantity' => 5]];
        $this->repository->removeFromPanier($panier, 1);
        $this->assertArrayNotHasKey(1, $panier);
        $this->assertArrayHasKey(2, $panier); 
    }
}
