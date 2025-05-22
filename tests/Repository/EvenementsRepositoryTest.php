<?php

namespace App\Tests\Repository;

use App\Entity\Evenements;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EvenementsRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::$container->get('doctrine')->getManager();
    }

    public function testFindByFiltersWithNoFiltersReturnsAll()
    {
        $repository = $this->entityManager->getRepository(Evenements::class);
        $results = $repository->findByFilters();

        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        $this->assertContainsOnlyInstancesOf(Evenements::class, $results);
    }

    public function testFindByFiltersWithSearch()
    {
        $repository = $this->entityManager->getRepository(Evenements::class);
        $searchTerm = 'Olympics';

        $results = $repository->findByFilters($searchTerm);

        $this->assertIsArray($results);
        foreach ($results as $event) {
            $this->assertStringContainsStringIgnoringCase($searchTerm, $event->getNomEvenement());
        }
    }

    public function testFindByFiltersWithDate()
    {
        $repository = $this->entityManager->getRepository(Evenements::class);
        $filterDate = '2024-07-';

        $results = $repository->findByFilters(null, $filterDate);

        $this->assertIsArray($results);
        foreach ($results as $event) {
            $this->assertStringStartsWith($filterDate, $event->getDateEvenement()->format('Y-m-d'));
        }
    }

    public function testFindByFiltersWithSearchAndDate()
    {
        $repository = $this->entityManager->getRepository(Evenements::class);
        $searchTerm = 'Olympics';
        $filterDate = '2024-07-';

        $results = $repository->findByFilters($searchTerm, $filterDate);

        $this->assertIsArray($results);
        foreach ($results as $event) {
            $this->assertStringContainsStringIgnoringCase($searchTerm, $event->getNomEvenement());
            $this->assertStringStartsWith($filterDate, $event->getDateEvenement()->format('Y-m-d'));
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
