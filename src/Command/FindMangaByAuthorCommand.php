<?php

namespace App\Command;

use App\Repository\Manga\MangaRepository;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:find-manga-by-author')]
class FindMangaByAuthorCommand extends Command
{
	private MangaRepository $mangaRepository;

	public function __construct(MangaRepository $mangaRepository)
	{
		parent::__construct();
		$this->mangaRepository = $mangaRepository;
	}

	protected function configure()
	{
		$this->addArgument('author', InputArgument::REQUIRED, 'The author of the manga to find');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		try {
			$mangaAuthor = $input->getArgument('author');

			$mangaByAuthor = $this->mangaRepository->findByAuthor($mangaAuthor);

			if (empty($mangaByAuthor))
			{
				$output->writeln('No manga in database with the given author');
				return Command::SUCCESS;
			}
			foreach ($mangaByAuthor as $mangaItem) {
				$mangaAuthors = $mangaItem->getMangaAuthors();
				$authors = [];
				foreach ($mangaAuthors as $mangaAuthor) {
					$author = $mangaAuthor->getAuthor();
					$role = $mangaAuthor->getRole();
					$authors[] = $role . ": " . $author->getName();
				}
				$authorsString = implode(', ', $authors);
				$rows[] = [
					$authorsString,
					$mangaItem->getName()	
				];
			}
			
			$table = new Table($output);
			$table
				->setHeaders(['Author', 'Manga name'])
				->setRows($rows);
			$table->render();
			return Command::SUCCESS;
		} catch (\Exception $error) {
			$output->writeln('<fg=red>Error: </>' . $error->getMessage());
			return Command::FAILURE;
		}
	}
}