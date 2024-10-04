<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

final class LastConnectionListener
{
    public function __construct(private readonly EntityManagerInterface $manager)
    {
    }
    #[AsEventListener(event: 'security.interactive_login')]
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        // Get the current connected user
        $user = $event->getAuthenticationToken()->getUser();

        // Check if user is instance of User
        if ($user instanceof User) {
            // Update the lastconnectedat property with current datetime
            $user->setLastConnectedAt(new \DateTimeImmutable());
            // Update the current user lastconnectedat property in the database
            $this->manager->flush();
        }
    }
}
