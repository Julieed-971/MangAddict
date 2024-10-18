<?php

namespace App\Controller\Manga;

use App\Entity\Manga\Manga;
use App\Entity\Manga\Rating;
use App\Entity\Manga\Review;
use App\Form\RatingType;
use App\Repository\Manga\RatingRepository;
use App\Repository\Manga\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/manga')]
class MangaController extends AbstractController
{
	private RatingRepository $ratingRepository;
	private ReviewRepository $reviewRepository;

	public function __construct(RatingRepository $ratingRepository, ReviewRepository $reviewRepository) {
		$this->ratingRepository = $ratingRepository;
		$this->reviewRepository = $reviewRepository;
	}
	#[Route('/{id}', name: 'app_manga_display', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
	public function display(?Manga $manga, Request $request, EntityManagerInterface $entityManager): Response
	{
		// Get currently connected user's manga rating if exist
		$currentRating = null;
		if ($this->getUser()) {
			$currentRating = $this->ratingRepository->findOneBy([
				'user' =>$this->getUser(),
				'manga' => $manga]);
		}

		// Create a form for rating only if rating is null
		$rating = new Rating();
	
		// If user already rated set the rating
		if ($currentRating) {
			$rating->setNote($currentRating->getNote());
		}

		$form = $this->createForm(RatingType::class, $rating);
		// Handle form submission
		$form->handleRequest($request);
			
		if ($form->isSubmitted() && $form->isValid()) { 
			$user = $this->getUser();
			$rating->setUser($user);
			$rating->setManga($manga);

			// Update or create rating and save to database
			if ($currentRating) {
				$currentRating->setNote($rating->getNote());
				$entityManager->persist($currentRating);
			} else {
			$entityManager->persist($rating);
			}
		$entityManager->flush();

		return $this->redirectToRoute('app_manga_display', ['id' => $manga->getId()]);
		}

		return $this->render('/manga/display.html.twig', [
			'manga' => $manga,
			'ratingForm' => $form,
			'currentRating' => $currentRating ? $currentRating->getNote() : null,
		]);
	}
}