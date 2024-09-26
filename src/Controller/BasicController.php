<?php

namespace App\Controller;

use App\Service\WelcomeMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BasicController extends AbstractController
{
    #[Route('/', name: 'app_basic')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'BasicController',
        ]);
    }
    #[Route('/Bienvenue', name:'app_welcome')]
    public function greet(WelcomeMessage $welcomeMessage): Response
    {
        // Call the method on the message object
        $welcomeMsg = $welcomeMessage->getWelcomeMessage('Username');
        return $this->render('welcome.html.twig', [
            'welcomeMsg' => $welcomeMsg,
        ]);
    }
}
