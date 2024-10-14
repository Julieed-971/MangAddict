<?php

namespace App\Entity\Manga;

use App\Enum\MangaType;
use App\Enum\MangaGenre;
use App\Enum\MangaPublicationStatus;
use App\Repository\Manga\MangaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: '`manga`')]
#[ORM\UniqueConstraint(name: 'unique_author_name', columns: ['name'])]
#[ORM\Entity(repositoryClass: MangaRepository::class)]
class Manga 
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	// name
	#[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

	// image
	#[Assert\NotBlank()]
    #[Assert\Url()]
    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

	// type
	#[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
	private ?string $type = null;

	// startDate
	#[Assert\Type(type: 'integer')]
    #[Assert\NotBlank()]
    #[ORM\Column]
    private ?int $startDate = null;

	// status
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
	private ?string $status = null;

	// volume
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $volumesNumber = null;

	// genres
    #[ORM\Column(type: 'json')]
	private array $genres = [];

	// description
	#[Assert\Length(min: 50)]
    #[Assert\NotBlank()]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, MangaAuthor>
     */
    #[ORM\OneToMany(targetEntity: MangaAuthor::class, mappedBy: 'manga', orphanRemoval: true, cascade: ['persist'])]
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

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getStartDate(): ?int
    {
        return $this->startDate;
    }

    public function setStartDate(int $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    
	public function getStatus(): ?string
	{
		return $this->status;
	}

	public function setStatus(string $status): self
	{
		$this->status = $status;
		return $this;
	}

	public function getVolumesNumber(): ?int
	{
		return $this->volumesNumber;
	}

	public function setVolumesNumber(int $volumesNumber): self
	{
		$this->volumesNumber = $volumesNumber;
		return $this;
	}

	public function getGenres(): array
	{
		return $this->genres;
	}

	public function setGenres(array $genres): self
	{
		$this->genres = $genres;
		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(string $description): self
	{
		$this->description = $description;
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
            $mangaAuthor->setManga($this);
        }

        return $this;
    }

    public function removeMangaAuthor(MangaAuthor $mangaAuthor): static
    {
        if ($this->mangaAuthors->removeElement($mangaAuthor)) {
            // set the owning side to null (unless already changed)
            if ($mangaAuthor->getManga() === $this) {
                $mangaAuthor->setManga(null);
            }
        }

        return $this;
    }
}
