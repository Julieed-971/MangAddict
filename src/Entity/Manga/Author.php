<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

	#[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, MangaAuthor>
     */
    #[ORM\OneToMany(targetEntity: MangaAuthor::class, mappedBy: 'author', orphanRemoval: true)]
    private Collection $mangaAuthors;

    public function __construct()
    {
        $this->mangaAuthors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, MangaAuthor>
     */
    public function getMangaAuthors(): Collection
    {
        return $this->mangaAuthors;
    }

    public function addMangaAuthor(MangaAuthor $mangaAuthor): static
    {
        if (!$this->mangaAuthors->contains($mangaAuthor)) {
            $this->mangaAuthors->add($mangaAuthor);
            $mangaAuthor->setAuthor($this);
        }

        return $this;
    }

    public function removeMangaAuthor(MangaAuthor $mangaAuthor): static
    {
        if ($this->mangaAuthors->removeElement($mangaAuthor)) {
            // set the owning side to null (unless already changed)
            if ($mangaAuthor->getAuthor() === $this) {
                $mangaAuthor->setAuthor(null);
            }
        }

        return $this;
    }
}
