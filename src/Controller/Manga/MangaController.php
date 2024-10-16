<?php

namespace App\Controller\Manga;

use App\Entity\Manga\Manga;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/manga')]
class MangaController extends AbstractController
{
	#[Route('/{id}', name: 'app_manga_display', requirements: ['id' => '\d+'], methods: ['GET'])]
	public function display(?Manga $manga): Response
	{
		return $this->render('/manga/display.html.twig', [
			'manga' => $manga,
		]);
	}
}