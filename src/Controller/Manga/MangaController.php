<?php

namespace App\Controller\Manga;

use App\Entity\Manga\Manga;
use App\Entity\Manga\Rating;
use App\Entity\Manga\Review;
use App\Form\RatingType;
use App\Form\ReviewType;
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
		$currentReview = null;
		if ($this->getUser()) {
			$currentRating = $this->ratingRepository->findOneBy([
				'user' => $this->getUser(),
				'manga' => $manga]);
			$currentReview = $this->reviewRepository->findOneBy([
				'user' => $this->getUser(),
				'manga' => $manga]);
		}

		// Create a form for rating 
		$rating = new Rating();
		
		// Create a review form
		$review = new Review();

		// If user already rated set the rating
		if ($currentRating) {
			$rating->setNote($currentRating->getNote());
		}

		// If user already reviewed the manga, set the review
		if ($currentReview) {
			$review->setContent($currentReview->getContent());
		}

		$ratingForm = $this->createForm(RatingType::class, $rating);
		$reviewForm = $this->createForm(ReviewType::class, $review);
		// Handle form submission
		$ratingForm->handleRequest($request);
		$reviewForm->handleRequest($request);
			
		if ($ratingForm->isSubmitted() && $ratingForm->isValid()) { 
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
		// $entityManager->flush();
		
		if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
			$user = $this->getUser();
			$review->setUser($user);
			$review->setManga($manga);

			// Update or create review and save it to database
			if ($currentReview) {
				$currentReview->setContent($review->getContent());
				$entityManager->persist($currentReview);
			} else {
				$entityManager->persist($review);
			}
		$entityManager->flush();
		}
		return $this->redirectToRoute('app_manga_display', ['id' => $manga->getId()]);
		}

		return $this->render('/manga/display.html.twig', [
			'manga' => $manga,
			'ratingForm' => $ratingForm,
			'reviewForm' => $reviewForm,
			'currentRating' => $currentRating ? $currentRating->getNote() : null,
			'currentReview' => $currentReview ? $currentReview->getContent() : null,
		]);
	}
}