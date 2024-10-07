<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:list-inactive-users')]
class ListInactiveUsersCommand extends Command
{
	// Inject entity manager to interact with database
	private EntityManagerInterface $manager;

	public function __construct(EntityManagerInterface $manager)
	{
		parent::__construct();
		$this->manager = $manager;
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
		
		// Query the users in db
		// where the lastConnectedAt date is equal
		// or superior to the current datetime minus the number of months
		$users = $this->manager->getRepository(User::class)
			->createQueryBuilder('u')
			->where('u.lastConnectedAt IS NULL OR u.lastConnectedAt <= :date')
			->setParameter('date', $date)
			->getQuery()
			->getResult();
		
		// List and output the users
		if (empty($users))
		{
			$output->writeln('No inactive users found');
		} else {
			$table = new Table($output);
			$table->setHeaders(['First Name', 'Last Name', 'Email', 'LastConnectedAt']);

			foreach ($users as $user) {
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