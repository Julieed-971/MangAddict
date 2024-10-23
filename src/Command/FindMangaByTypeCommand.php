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

#[AsCommand(name: 'app:find-manga-by-type')]
class FindMangaByTypeCommand extends Command
{
	private MangaRepository $mangaRepository;

	public function __construct(MangaRepository $mangaRepository)
	{
		parent::__construct();
		$this->mangaRepository = $mangaRepository;
	}

	protected function configure()
	{
		$this->addArgument('type', InputArgument::REQUIRED, 'The type of the manga to find');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		try {
			$mangaType = $input->getArgument('type');

			$mangaByType = $this->mangaRepository->findByType($mangaType);

			if (empty($mangaByType))
			{
				$output->writeln('No manga in database with the given type');
				return Command::SUCCESS;
			}
		
			$rows = [];
			foreach ($mangaByType as $manga) {
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