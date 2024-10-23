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

#[AsCommand(name: 'app:find-manga-by-name')]
class FindMangaByNameCommand extends Command
{
	private MangaRepository $mangaRepository;

	public function __construct(MangaRepository $mangaRepository)
	{
		parent::__construct();
		$this->mangaRepository = $mangaRepository;
	}

	protected function configure()
	{
		$this->addArgument('name', InputArgument::REQUIRED, 'The name of the manga to find');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		try {
			$mangaName = $input->getArgument('name');

			$mangaByName = $this->mangaRepository->findByName($mangaName);

			if (empty($mangaByName))
			{
				$output->writeln('No manga in database with the given name');
				return Command::SUCCESS;
			}
		
			$rows = [];
			foreach ($mangaByName as $manga) {
				$mangaAuthors = $manga->getMangaAuthors();
				$authors = [];
				foreach ($mangaAuthors as $mangaAuthor) {
					$author = $mangaAuthor->getAuthor();
					$role = $mangaAuthor->getRole();
					$authors[] = $role . ": " . $author->getName();
				}
				$authorsString = implode(', ', $authors);
				$rows[] = [
					$manga->getName(),
					$manga->getType(),
					$authorsString
				];
			}
			$table = new Table($output);
			$table
				->setHeaders(['Manga name', 'Type', 'Authors'])
				->setRows($rows);
			$table->render();
			return Command::SUCCESS;
		} catch (\Exception $error) {
			$output->writeln('<fg=red>Error: </>' . $error->getMessage());
			return Command::FAILURE;
		}
	}
}