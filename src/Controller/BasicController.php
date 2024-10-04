<?php

namespace App\Controller;

use App\Service\WelcomeMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BasicController extends AbstractController
{
    #[Route('/', name: 'app_basic')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'BasicController',
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/bienvenue', name:'app_welcome')]
    public function greet(WelcomeMessage $welcomeMessage): Response
    {
        // Call the method on the message object
        $welcomeMsg = $welcomeMessage->getWelcomeMessage();
        return $this->render('welcome/welcome.html.twig', [
            'welcomeMsg' => $welcomeMsg,
        ]);
    }
}
