<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:list-inactive-users')]
class ListInactiveUsersCommand extends Command
{
	// Inject entity userRepository to interact with database
	private UserRepository $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		parent::__construct();
		$this->userRepository = $userRepository;
	}

	protected function configure(): void
	{
		$this
			// Configure the month argument
			->addArgument('months', InputArgument::OPTIONAL, 'The number of months since users last connected', 1);
	}
	
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		// Get the number of months from the input
		$months = (int) $input->getArgument('months');

		// Calculate the datetime to use to retrieve users
		$date = (new \DateTimeImmutable())->modify("-$months months");
		
		// Use the findByInactiveSince method in user repository to query inactive users
		$inactiveUsers = $this->userRepository->findByInactiveSince($date);
		
		// List and output the users
		if ($inactiveUsers === null)
		{
			$output->writeln('No inactive users found');
		} else {
			$table = new Table($output);
			$table->setHeaders(['First Name', 'Last Name', 'Email', 'LastConnectedAt']);

			foreach ($inactiveUsers as $user) {
				$lastConnectedAt = $user->getLastConnectedAt();
				$formattedDate = $lastConnectedAt ? $lastConnectedAt->format('Y-m-d H:i:s') : 'Never';
				$rows[] = [$user->getFirstname(), $user->getLastname(), $user->getEmail(), $formattedDate];
			}
			$table->setRows($rows);
			$table->render();
		}
		return Command::SUCCESS;
	}
}