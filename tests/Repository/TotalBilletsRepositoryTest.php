<?php

namespace App\Tests\Repository;

use App\Entity\TotalBillets;
use App\Repository\TotalBilletsRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TotalBilletsRepositoryTest extends KernelTestCase
{
    private $repository;
    private $em;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = self::$container->get('doctrine')->getManager();
        $this->repository = $this->em->getRepository(TotalBillets::class);
    }

    public function testUpdateBilletsSaleSolo()
    {
        // Assure-toi qu'un enregistrement avec id = 1 existe
        /** @var TotalBillets $billets */
        $billets = $this->repository->find(1);

        $initialSolo = $billets->getSolo();
        $initialVendus = $billets->getVendus();
        $initialTotal = $billets->getPrixTotalRecuperes();

        $quantite = 3;
        $prix = 50.0;

        $this->repository->updateBilletsSale(1, $quantite, $prix);

        $this->em->refresh($billets); // Recharger depuis la DB

        $this->assertEquals($initialSolo + $quantite, $billets->getSolo());
        $this->assertEquals($initialVendus + $quantite, $billets->getVendus());
        $this->assertEquals($initialTotal + ($quantite * $prix), $billets->getPrixTotalRecuperes());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->em->close();
        $this->em = null;
    }
}
