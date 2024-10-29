<?php

namespace App\Repository\Manga;

use App\Entity\Manga\Manga;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Manga>
 * @method Manga|null find($id, $lockMode = null, $lockVersion = null)
 * @method Manga|null findOneBy(array $criteria, array $orderBy = null)
 * @method Manga[]    findAll()
 * @method Manga[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MangaRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Manga::class);
	}

	// FindByName
	public function findByName(string $name)
	{
		$mangaByName = $this->createQueryBuilder('mbn')
			->where('mbn.name LIKE :name')
			->setParameter('name', '%' . $name . '%')
			->getQuery()
			->getResult();
		
			return !empty($mangaByName) ? $mangaByName : null;
	}

	// FindByType
	public function findByType(string $type)
	{
		$mangaByType = $this->createQueryBuilder('mbt')
			->where('mbt.type LIKE :type')
			->setParameter('type', '%' . $type . '%')
			->getQuery()
			->getResult();

		return !empty($mangaByType) ? $mangaByType : null;
	}

	// FindByGenre
	public function findByGenre(array $genres)
	{
		$queryBuilder = $this->createQueryBuilder('mbg');

		foreach ($genres as $index => $genre) {
			// Generate unique parameter names for each genre to avoid conflicts.
			$parameterName = 'genre' . $index;
			$queryBuilder->andWhere("JSON_CONTAINS(LOWER(mbg.genres), LOWER(:$parameterName)) = 1")
						 ->setParameter($parameterName, json_encode($genre, JSON_UNESCAPED_UNICODE));
		}
		$mangaByGenre = $queryBuilder->getQuery()->getResult();
		
		return !empty($mangaByGenre) ? $mangaByGenre : null;
	}

	// FindByAuthor
	public function findByAuthor(string $authorName)
	{
		$mangaByAuthor = $this->createQueryBuilder('mba')
			->join('mba.mangaAuthors', 'ma')
			->join('ma.author', 'a')
			->where('a.name LIKE :authorName')
			->setParameter('authorName', '%' . $authorName . '%')
			->getQuery()
			->getResult();

		return !empty($mangaByAuthor) ? $mangaByAuthor : null;
	}

	// FindByStatus

	public function getFilteredMangas(array $filterData)
	{
		$mangasList = [];
		$results = [
			'typeExists' => false,
			'genreExists' => false,
			'authorExists' => false,
		];

		if (!empty($filterData['type'])) {
			$mangasList = $this->findByType($filterData['type']);
			$results['typeExists'] = !empty($mangasList);
		}
		if (!empty($filterData['genre'])) {
			$mangaByGenre = $this->findByGenre($filterData['genre']);
			if (empty($mangaByGenre)) {
				$mangaByGenre = [];
			}
			if (empty($mangasList)) {
				$mangasList = $mangaByGenre;
			} else {
				// If the mangasList contains already filtered manga, get the mangas by genre and keep the one that matches the list
				$mangasList = array_uintersect($mangasList, $mangaByGenre, function ($a, $b) {
					return $a->getId() <=> $b->getId();
				});
			}
			$results['genreExists'] = !empty($mangaByGenre);
		}
		if (!empty($filterData['author'])) {
			$mangaByAuthor = $this->findByAuthor($filterData['author']);
			if (empty($mangaByAuthor)) {
				$mangaByAuthor = [];
			}
			if (empty($mangasList)) {
				$mangasList = $mangaByAuthor;
			} else {
				// If the mangasList contains already filtered manga, get the mangas by genre and keep the one that matches the list
				$mangasList = array_uintersect($mangasList, $mangaByAuthor, function ($a, $b) {
					return $a->getId() <=> $b->getId();
				});
			}
			$results['authorExists'] = !empty($mangaByAuthor);
		} 

		return [
			'mangasList' => $mangasList,
			'results' => $results,
		];
	}


}