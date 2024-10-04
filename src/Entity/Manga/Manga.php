<?php

namespace App\Entity;

use App\Enum\MangaType;
use App\Enum\MangaGenre;
use App\Enum\MangaPublicationStatus;
use App\Repository\MangaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MangaRepository::class)]
#[ORM\Table(name: '`manga`')]
class Manga 
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	// title
	#[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

	// image
	#[Assert\NotBlank()]
    #[Assert\Url()]
    #[ORM\Column(length: 255)]
    private ?string $cover = null;

	// type
    #[ORM\Column(length: 255)]
	private ?MangaType $type = null;

	// startDate
    #[Assert\NotBlank()]
    #[ORM\Column]
    private ?\DateTimeImmutable $startDate = null;

	// status
    #[ORM\Column(length: 255)]
	private ?MangaPublicationStatus $status = null;

	// volume
    #[Assert\Type(type: 'integer')]
    #[Assert\NotBlank()]
    #[ORM\Column]
    private ?int $volumeNumber = null;

	// author
	#[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $author = null;

	// genre
    #[ORM\Column(length: 255)]
	private ?MangaGenre $genre = null;

	// description
	#[Assert\Length(min: 50)]
    #[Assert\NotBlank()]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $plot = null;
	public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): self
    {
        $this->cover = $cover;
        return $this;
    }

    public function getType(): ?MangaType
    {
        return $this->type;
    }

    public function setType(MangaType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    
	public function getStatus(): ?MangaPublicationStatus
	{
		return $this->status;
	}

	public function setStatus(MangaPublicationStatus $status): self
	{
		$this->status = $status;
		return $this;
	}

	public function getVolumeNumber(): ?int
	{
		return $this->volumeNumber;
	}

	public function setVolumeNumber(int $volumeNumber): self
	{
		$this->volumeNumber = $volumeNumber;
		return $this;
	}

	public function getAuthor(): ?string
	{
		return $this->author;
	}

	public function setAuthor(string $author): self
	{
		$this->author = $author;
		return $this;
	}

	public function getGenre(): ?MangaGenre
	{
		return $this->genre;
	}

	public function setGenre(MangaGenre $genre): self
	{
		$this->genre = $genre;
		return $this;
	}

	public function getPlot(): ?string
	{
		return $this->plot;
	}

	public function setPlot(string $plot): self
	{
		$this->plot = $plot;
		return $this; 
	}
}
