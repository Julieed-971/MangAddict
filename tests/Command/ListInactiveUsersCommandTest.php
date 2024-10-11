<?php

namespace App\Tests\Command;

use App\Entity\User;
use App\Command\ListInactiveUsersCommand;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ListInactiveUsersCommandTest extends TestCase
{
	public function userDataProvider()
    {
        $user1 = $this->createMock(User::class);
        $user1->method('getLastConnectedAt')->willReturn(new \DateTimeImmutable('2022-01-01'));
        $user1->method('getFirstname')->willReturn('Janette');
        $user1->method('getLastname')->willReturn('Doe');
        $user1->method('getEmail')->willReturn('janette.doe@example.com');

        $user2 = $this->createMock(User::class);
        $user2->method('getLastConnectedAt')->willReturn(new \DateTimeImmutable('2024-06-01'));
        $user2->method('getFirstname')->willReturn('Jane');
        $user2->method('getLastname')->willReturn('Doe');
        $user2->method('getEmail')->willReturn('jane.doe@example.com');

        return [
            'no inactive users' => [
                'inactiveUsers' => [],
            ],
            'some inactive users' => [
                'inactiveUsers' => [$user1, $user2],
            ],
        ];
    }

	/**
     * @dataProvider userDataProvider
     */
	public function testExecuteListInactiveUsersCommand($inactiveUsers)
	{
		// Mock the UserRepository to use the findByInactiveSince method
		$userRepository = $this->createMock(UserRepository::class);
		/** @var UserRepository $userRepository */
		$userRepository->method('findByInactiveSince')->willReturn($inactiveUsers);

		// Create the command and inject the mock user repository
		$command = new ListInactiveUsersCommand($userRepository);

		// Set up the application and command tester
		$application = new Application();
		$application->add($command);
		$commandTester = new CommandTester($application->find('app:list-inactive-users'));

		// Execute the command 
		$commandTester->execute([
			'command' => 'app:list-inactive-users',
			'months' => 6,
		]);

		// Assert the output
		$output = $commandTester->getDisplay();
		if (empty($inactiveUsers)) {
            $this->assertStringContainsString('No inactive users found', $output);
        } else {
			$this->assertStringContainsString('Janette', $output);
			$this->assertStringContainsString('Doe', $output);
			$this->assertStringContainsString('janette.doe@example.com', $output);
			$this->assertStringContainsString('2022-01-01 00:00:00', $output);
			$this->assertStringContainsString('Jane', $output);
			$this->assertStringContainsString('jane.doe@example.com', $output);
			$this->assertStringContainsString('2024-06-01 00:00:00', $output);
		}
	}
}