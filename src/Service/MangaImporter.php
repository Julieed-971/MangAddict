<?php

namespace App\Service;

use App\Entity\Manga\Manga;
use App\Entity\Manga\Author;
use App\Entity\Manga\MangaAuthor;
use App\Enum\MangaType;
use App\Enum\MangaGenre;
use App\Enum\MangaPublicationStatus;
use App\Repository\Manga\AuthorRepository;
use App\Repository\Manga\MangaRepository;
use App\Repository\MangaRepository as RepositoryMangaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MangaImporter
{
	private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
	private AuthorRepository $authorRepository;
	private MangaRepository $mangaRepository;
	private $authorCache = [];

    public function __construct(
		EntityManagerInterface $entityManager, 
		ValidatorInterface $validator, 
		AuthorRepository $authorRepository, 
		MangaRepository $mangaRepository
		)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
		$this->authorRepository = $authorRepository;
		$this->mangaRepository = $mangaRepository;
    }

	public function importFromJson(string $filePath): int
	{
		$importedMangaCount = 0;
		// Check if file exists at specified path
		if (!file_exists($filePath)) {
			throw new \InvalidArgumentException('The specified file does not exist or contains a typo.');
		}

		$jsonData = file_get_contents($filePath);
		$importedMangaCount = $this->mangaDeserializer($jsonData);
		
		return  $importedMangaCount;
	}

	public function mangaDeserializer(string $jsonData): int
	{
	$normalizers = [new ObjectNormalizer()];
	$mangaSerializer = new Serializer($normalizers);
	$importedMangaCount = 0;
	
	$mangas = json_decode($jsonData, true);
	if (json_last_error() !== JSON_ERROR_NONE) {
		throw new \InvalidArgumentException('The file does not contain valid JSON: ' . json_last_error_msg());
	}
	foreach ($mangas as $mangaItem) {
		// Handle authors
		if (!isset($mangaItem['author'])) {
			throw new \RuntimeException('Author key is missing in manga item: ' . json_encode($mangaItem));
		}

		// Provide a default value for startDate if it is null
        if (!isset($mangaItem['startDate']) || $mangaItem['startDate'] === null) {
            $mangaItem['startDate'] = 0;

        }// Provide a default value for status if it is null
        if (!isset($mangaItem['status']) || $mangaItem['status'] === null) {
            $mangaItem['status'] = 'unknown';
        }
		// Provide a default value for volumesNumber if it is null
        if (!isset($mangaItem['volumesNumber']) || $mangaItem['volumesNumber'] === null) {
            $mangaItem['volumesNumber'] = 0;
        }
		// Check if manga already exists
        $existingManga = $this->mangaRepository->findByName($mangaItem['name']);
        if ($existingManga) {
            // Update existing manga if necessary
            $manga = $existingManga;
        } else {
            $manga = $mangaSerializer->denormalize($mangaItem, Manga::class);
        }

		if (isset($mangaItem['author'])) {
			$this->handleAuthors($manga, $mangaItem['author']);
		}

		// Validate and persist the manga entity using Doctrine
		$errors = $this->validator->validate($manga);
		if (count($errors) > 0) {
			// Handle validation errors
			continue;
		}
		$this->entityManager->persist($manga);
		$importedMangaCount++;
		}
	$this->entityManager->flush();
	return $importedMangaCount;
	}

	private function handleAuthors(Manga $manga, array $authorData): void
	{
		// Check if story author exists in database
		$storyAuthorName = $authorData['story'];
		$storyAuthor = $this->getAuthor($storyAuthorName);

		// Create a MangaAuthor entity instance for the story author
		$mangaAuthorStory = new MangaAuthor();
		$mangaAuthorStory->setManga($manga);
		$mangaAuthorStory->setAuthor($storyAuthor);
		$mangaAuthorStory->setRole('story');
		$this->entityManager->persist($mangaAuthorStory);
		// Add story author relationship to manga
		$manga->addMangaAuthor($mangaAuthorStory);

		// Check if art author exists and reuse the story author if they are the same
		$artAuthorName = $authorData['art'];
		if ($artAuthorName === $storyAuthorName) {
			$artAuthor = $storyAuthor;
		} else {
			$artAuthor = $this->getAuthor($artAuthorName);
		}

		$mangaAuthorArt = new MangaAuthor();
		$mangaAuthorArt->setManga($manga);
		$mangaAuthorArt->setAuthor($artAuthor);
		$mangaAuthorArt->setRole('art');

		$this->entityManager->persist($mangaAuthorArt);
		// Add art author relationship to manga
		$manga->addMangaAuthor($mangaAuthorArt);
	}

	private function getAuthor(string $authorName): Author
    {
        // Check local cache first
        if (isset($this->authorCache[$authorName])) {
            return $this->authorCache[$authorName];
        }

        // Check database if not found in cache
        $author = $this->authorRepository->findOneBy(['name' => $authorName]);
        if (!$author) {
            $author = new Author();
            $author->setName($authorName);
            $this->entityManager->persist($author);
        }

        // Add to local cache
        $this->authorCache[$authorName] = $author;

        return $author;
    }
}


		// $mangaArray = json_decode($jsonData, true);
		// if (json_last_error() !== JSON_ERROR_NONE) {
		// 	throw new \InvalidArgumentException('The file does not contain valid JSON: ' . json_last_error_msg());
		// }

		// $importedMangaCount = 0;

		// foreach ($mangaArray as $data) {
		// 	$manga = new Manga();
		// 	$manga->setName($data['name']);
		// 	$manga->setImageUrl($data['imageUrl']);
		// 	$manga->setType(MangaType::from($data['type']));
		// 	$manga->setStartDate((int)$data['startDate']);
		// 	$manga->setStatus(MangaPublicationStatus::from($data['status']));
		// 	$manga->setVolumesNumber((int)$data['volumesNumber']);
			
		// 	// Handle array of genre and map it to existing MangaGenre enum 
		// 	if (isset($data['genres'])) {
		// 		$genreEnums = [];
		// 		foreach ($data['genres'] as $genre) {
		// 			// Checks if enum value exists
		// 			if (MangaGenre::tryFrom($genre)) {
		// 				$genreEnums[] = MangaGenre::from($genre);
		// 			}
		// 		}
		// 		$manga->setGenres($genreEnums);
		// 	}
		// 	$manga->setDescription($data['description']);

		// 	// Handle authors
		// 	if (isset($data['author'])) {
			// 	// Check if story author exists in database
			// 	$storyAuthorName = $data['author']['story'];
			// 	$storyAuthor = $this->authorRepository->findOneBy(['name' => $storyAuthorName]);

			// 	if (!$storyAuthor) {
			// 		$storyAuthor = new Author();
			// 		$storyAuthor->setName($storyAuthorName);
			// 		$this->entityManager->persist($storyAuthor);
			// 	}

			// 	// Create a MangaAuthor entity instance for the story author
			// 	$mangaAuthorStory = new MangaAuthor();
			// 	$mangaAuthorStory->setManga($manga);
			// 	$mangaAuthorStory->setAuthor($storyAuthor);
			// 	$mangaAuthorStory->setRole('story');

			// 	// Add story author relationship to manga
			// 	$manga->addMangaAuthor($mangaAuthorStory);

			// 	// Checks if art author exists and is different from story author
			// 	if (isset($data['author']['art']) && $data['author']['art'] !== $storyAuthorName) {
			// 		$artAuthorName = $data['author']['art'];
			// 		$artAuthor = $this->authorRepository->findOneBy(['name' => $artAuthorName]);

			// 		if (!$artAuthor) {
			// 			$artAuthor = new Author();
			// 			$artAuthor->setName($artAuthorName);
			// 			$this->entityManager->persist($artAuthor);
			// 		}

			// 		$mangaAuthorArt = new MangaAuthor();
			// 		$mangaAuthorArt->setManga($manga);
			// 		$mangaAuthorArt->setAuthor($artAuthor);
			// 		$mangaAuthorArt->setRole('art');

			// 		// Add art author relationship to manga
			// 		$manga->addMangaAuthor($mangaAuthorArt);
			// 	}
			// }

			// // Validate and persist the manga entity using Doctrine
			// $errors = $this->validator->validate($manga);
			// if (count($errors) > 0) {
			// 	// Handle validation errors
			// 	continue;
			// }
		// 	// Persist the manga entity
		// 	$this->entityManager->persist($manga);
		// 	$importedMangaCount++;
		// }
		// // Flush all changes to the database
		// $this->entityManager->flush();

		// return $importedMangaCount;
	// }

	