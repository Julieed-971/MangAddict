<?php

namespace App\Repository\Manga;

use App\Entity\Manga\Manga;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

	// FindByAuthor
	// FindByStatus
	// FindByGenre
}