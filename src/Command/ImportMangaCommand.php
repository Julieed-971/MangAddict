<?php

namespace App\Command;

use App\Service\MangaImporter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
	name: 'app:import-manga',
	description: 'Import manga from a JSON file into the database using the MangaImporter service',
)]
class ImportMangaCommand extends Command
{
	private MangaImporter $mangaImporter;

	public function __construct(MangaImporter $mangaImporter)
	{
		parent::__construct();
		$this->mangaImporter = $mangaImporter;
	}

	protected function configure() : void
	{
		$this
		// Configure the filepath argument
		->addArgument('filePath', InputArgument::REQUIRED, 'The path of the file to import mangas from');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		// Get the file path to import manga from the input
		$filePath = (string) $input->getArgument('filePath');

		try {
			$importedMangaCount = $this->mangaImporter->importFromJson($filePath);
			$output->writeln(sprintf('%d Manga data imported successfully.', $importedMangaCount));
		} catch (\Exception $error) {
			// Handle exceptions and display error messages
			$output->writeln('Error importing manga data: ' . $error->getMessage());
			return Command::FAILURE;
		}

		return Command::SUCCESS;
	}
}