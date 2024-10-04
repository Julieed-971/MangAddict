<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class WelcomeMessage
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    public function getWelcomeMessage(): string
    {
        // Get the currently logged-in user
        $token = $this->tokenStorage->getToken();

        if ($token) {
            $user = $token->getUser();

            if ($user instanceof User) {
                $firstname = $user->getFirstname();
                $message = 'Bienvenue sur MangAddict ' . $firstname .'';
                return $message;
            }
        }
    }
}