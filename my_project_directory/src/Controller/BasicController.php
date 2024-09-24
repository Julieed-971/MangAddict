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
        // Call the method on the message object
        $welcomeMessage = new WelcomeMessage();
        $welcomeMsg = $welcomeMessage->getWelcomeMessage('Username');
        return $this->render('base.html.twig', [
            'controller_name' => 'BasicController',
            'welcomeMsg'=> $welcomeMsg,
        ]);
    }
}
