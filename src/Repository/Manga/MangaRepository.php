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
	public function findByGenre(string $genre)
	{
		$mangaByGenre = $this->createQueryBuilder('mbg')
			->where('JSON_CONTAINS(LOWER(mbg.genres), LOWER(:genre)) = 1')
			->setParameter('genre', json_encode($genre))
			->getQuery()
			->getResult();
		
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

	public function getPaginatedMangas($filterData, PaginatorInterface $paginator, $page = 1, $limit = 16)
	{
		$queryBuilder = $this->createQueryBuilder('m');

		if (!empty($filterData['type'])) {
			$queryBuilder->where('m.type LIKE :type')
						 ->setParameter('type', '%' . $filterData['type'] . '%')
						 ->getQuery()
						 ->getResult();
		}
		if (!empty($filterData['genre'])) {
			$queryBuilder->andWhere('JSON_CONTAINS(m.genres, :genre) = 1')
						 ->setParameter('genre', json_encode($filterData['genre']));
		}
		if (!empty($filterData['author'])) {
			$queryBuilder->join('m.mangaAuthors', 'ma')
						 ->join('ma.author', 'a')
						 ->andWhere('a.name LIKE :author')
						 ->setParameter('author', '%' . $filterData['author'] . '%');
		}

		return $paginator->paginate(
			$queryBuilder,
			$page,
			$limit
			);
	}


}