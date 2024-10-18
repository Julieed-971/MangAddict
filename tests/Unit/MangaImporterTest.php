<?php

namespace App\Tests\Service;

use App\Repository\Manga\AuthorRepository;
use App\Repository\Manga\MangaRepository;
use PHPUnit\Framework\TestCase;
use App\Service\MangaImporter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MangaImporterTest extends TestCase
{
	// Set up testing environment
	/**
     * @var EntityManagerInterface|MockObject
     */
    private $entityManager;

    /**
     * @var ValidatorInterface|MockObject
     */
    private $validator;

    /**
     * @var AuthorRepository|MockObject
     */
    private $authorRepository;
	
	/**
     * @var MangaRepository|MockObject
     */
    private $mangaRepository;

    /**
     * @var MangaImporter
     */
    private $mangaImporter;

	protected function setUp(): void
	{
		// Create mock objects for the dependencies
		$this->entityManager = $this->createMock(EntityManagerInterface::class);
		$this->validator = $this->createMock(ValidatorInterface::class);
		$this->authorRepository = $this->createMock(AuthorRepository::class);
		$this->mangaRepository = $this->createMock(MangaRepository::class);

		// Instantiate the MangaImporter with the mock dependencies
		$this->mangaImporter = new MangaImporter(
			$this->entityManager,
			$this->validator,
			$this->authorRepository,
			$this->mangaRepository
		);
	}

	public function filePathDataProvider()
	{
		return [
			'short manga data' => [
				'filePath' => 'tests/Fixtures/valid_short_manga_data_v1.json',
				'expectedOutput' => 3
			],
			'long manga data' => [
				'filePath' => 'tests/Fixtures/valid_long_manga_data_v1.json',
				'expectedOutput' => 16
			],
		];
	}

	/**
	 * @dataProvider filePathDataProvider
	 */
	public function testMangaImporter(string $filePath, $expectedOutput)
	{
		$importedMangaCount = $this->mangaImporter->importFromJson($filePath);
		
		$this->assertEquals($expectedOutput, $importedMangaCount);
	}

	// Test if each property is of the right type

	// Test if data are correctly stored in the database
}

