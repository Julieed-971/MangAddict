<?php

namespace App\Entity\Manga;

use App\Repository\MangaAuthorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MangaAuthorRepository::class)]
class MangaAuthor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'mangaAuthors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Manga $manga = null;

    #[ORM\ManyToOne(inversedBy: 'mangaAuthors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Author $author = null;

    #[ORM\Column(length: 10)]
    private ?string $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManga(): ?Manga
    {
        return $this->manga;
    }

    public function setManga(?Manga $manga): static
    {
        $this->manga = $manga;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }
}
