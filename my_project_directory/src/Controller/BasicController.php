<?php

namespace App\Controller;

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
}
