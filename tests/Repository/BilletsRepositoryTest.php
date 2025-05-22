<?php

namespace App\Tests\Repository;

use App\Entity\Billets;
use App\Repository\BilletsRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BilletsRepositoryTest extends KernelTestCase
{
    private ?BilletsRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = static::getContainer()->get(BilletsRepository::class);
    }

    public function testGetSalesDataByBilletReturnsCorrectData(): void
    {
        $billet = $this->repository->find(1); // Adapte selon ta base
        $this->assertInstanceOf(Billets::class, $billet);

        $result = $this->repository->getSalesDataByBillet($billet);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('quantiteVendue', $result);
        $this->assertArrayHasKey('montantTotal', $result);

        $this->assertGreaterThanOrEqual(0, $result['quantiteVendue']);
        $this->assertGreaterThanOrEqual(0, $result['montantTotal']);
    }

    public function testGetSalesStatisticsReturnsArray(): void
    {
        $stats = $this->repository->getSalesStatistics();

        $this->assertIsArray($stats);
        $this->assertNotEmpty($stats);

        $first = $stats[0];
        $this->assertArrayHasKey('type', $first);
        $this->assertArrayHasKey('stock', $first);
        $this->assertArrayHasKey('quantiteVendue', $first);
        $this->assertArrayHasKey('reste', $first);
        $this->assertArrayHasKey('montantTotal', $first);
    }

    public function testGetSalesStatisticsWithTotalAddsTotalRow(): void
    {
        $stats = $this->repository->getSalesStatisticsWithTotal();

        $this->assertIsArray($stats);
        $this->assertNotEmpty($stats);

        $last = end($stats);

        $this->assertEquals('Total', $last['type']);
        $this->assertArrayHasKey('stock', $last);
        $this->assertArrayHasKey('quantiteVendue', $last);
        $this->assertArrayHasKey('reste', $last);
        $this->assertArrayHasKey('montantTotal', $last);

        // Optionnel : vérifier que le total est bien la somme des autres lignes
        $sumStock = 0;
        $sumVendus = 0;
        $sumReste = 0;
        $sumMontant = 0.0;

        foreach ($stats as $key => $row) {
            if ($key === array_key_last($stats)) {
                break;
            }
            $sumStock += $row['stock'];
            $sumVendus += $row['quantiteVendue'];
            $sumReste += $row['reste'];
            $sumMontant += $row['montantTotal'];
        }

        $this->assertEquals($sumStock, $last['stock']);
        $this->assertEquals($sumVendus, $last['quantiteVendue']);
        $this->assertEquals($sumReste, $last['reste']);
        $this->assertEquals($sumMontant, $last['montantTotal']);
    }

    public function testGetDetailedSalesDataReturnsArray(): void
    {
        $details = $this->repository->getDetailedSalesData();

        $this->assertIsArray($details);
        $this->assertNotEmpty($details);

        $first = $details[0];
        $this->assertArrayHasKey('type', $first);
        $this->assertArrayHasKey('stock', $first);
        $this->assertArrayHasKey('quantiteVendue', $first);
        $this->assertArrayHasKey('reste', $first);
        $this->assertArrayHasKey('montantTotal', $first);
        $this->assertArrayHasKey('billetsVendus', $first);
        $this->assertArrayHasKey('totalPrice', $first);
    }
}
