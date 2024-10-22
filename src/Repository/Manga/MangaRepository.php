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
	public function findByName(string $name): ?Manga
	{
		return $this->findOneBy(['name' => $name]);
	}

	public function getPaginatedMangas($filterData, PaginatorInterface $paginator, $page = 1, $limit = 16)
	{
		$queryBuilder = $this->createQueryBuilder('m');

		if (!empty($filterData['type'])) {
			$queryBuilder->andWhere('m.type LIKE :type')
						 ->setParameter('type', '%' . $filterData['type'] . '%');
		}
		if (!empty($filterData['genre'])) {
			$queryBuilder->andWhere('m.genres LIKE :genre')
						 ->setParameter('genre', '%' . $filterData['genre'] . '%');
		}
		if (!empty($filterData['author'])) {
			$queryBuilder->andWhere('m.mangaAuthor LIKE :author')
						 ->setParameter('author', '%' . $filterData['author'] . '%');
		}

		return $paginator->paginate(
			$queryBuilder,
			$page,
			$limit
			);
	}
	// FindByAuthor
	// FindByStatus
	// FindByGenre
}