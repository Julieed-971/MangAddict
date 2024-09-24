<?php

namespace App\Service;

class WelcomeMessage
{
    public function getWelcomeMessage($firstName): string
    {
        $message = 'Bienvenue sur MangAddict ' . $firstName .'';
        return $message;
    }
}