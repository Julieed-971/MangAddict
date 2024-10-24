<?php

namespace App\Command;

use App\Repository\Manga\MangaRepository;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:find-manga-by-genre')]
class FindMangaByGenreCommand extends Command
{
	private MangaRepository $mangaRepository;

	public function __construct(MangaRepository $mangaRepository)
	{
		parent::__construct();
		$this->mangaRepository = $mangaRepository;
	}

	protected function configure()
	{
		$this->addArgument('genre', InputArgument::IS_ARRAY | InputArgument::REQUIRED, 'The genre(s) of the manga to find');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		try {
			$mangaGenre = $input->getArgument('genre');

			$mangaByGenre = $this->mangaRepository->findByGenre($mangaGenre);

			if (empty($mangaByGenre))
			{
				$output->writeln('No manga in database with the given genre');
				return Command::SUCCESS;
			}
		
			$rows = [];
			foreach ($mangaByGenre as $manga) {
				$mangaAuthors = $manga->getMangaAuthors();
				$authors = [];
				foreach ($mangaAuthors as $mangaAuthor) {
					$author = $mangaAuthor->getAuthor();
					$role = $mangaAuthor->getRole();
					$authors[] = $role . ": " . $author->getName();
				}
				$authorsString = implode(', ', $authors);
				$mangaGenres = $manga->getGenres();
				$genresString = implode(', ', $mangaGenres);
				$rows[] = [
					$manga->getName(),
					$authorsString,
					$genresString
				];
			}
			$table = new Table($output);
			$table
				->setHeaders(['Manga name', 'Authors', 'Genre'])
				->setRows($rows);
			$table->render();
			return Command::SUCCESS;
		} catch (\Exception $error) {
			$output->writeln('<fg=red>Error: </>' . $error->getMessage());
			return Command::FAILURE;
		}
	}
}