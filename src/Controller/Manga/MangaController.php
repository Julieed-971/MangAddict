<?php

namespace App\Controller\Manga;

use App\Enum\MangaType;
use App\Enum\MangaGenre;
use App\Entity\Manga\Manga;
use App\Entity\Manga\Rating;
use App\Entity\Manga\Review;
use App\Form\MangaFilterType;
use App\Form\RatingType;
use App\Form\ReviewType;
use App\Repository\Manga\MangaRepository;
use App\Repository\Manga\RatingRepository;
use App\Repository\Manga\ReviewRepository;
use App\Service\CustomPaginator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/manga')]
class MangaController extends AbstractController
{
	private RatingRepository $ratingRepository;
	private ReviewRepository $reviewRepository;
	private MangaRepository $mangaRepository;
	private CustomPaginator $customPaginator;
	private LoggerInterface $logger;



	public function __construct(RatingRepository $ratingRepository, ReviewRepository $reviewRepository, MangaRepository $mangaRepository, CustomPaginator $customPaginator, LoggerInterface $logger) {
		$this->ratingRepository = $ratingRepository;
		$this->reviewRepository = $reviewRepository;
		$this->mangaRepository = $mangaRepository;
		$this->customPaginator = $customPaginator;
		$this->logger = $logger;
	}

	#[Route('', name: 'app_manga_index', methods: ['GET', 'POST'])]
	public function index(Request $request): Response
	{
		$mangaTypes = MangaType::cases();
		$mangaGenres = MangaGenre::cases();

		// Create filter form
		$filterForm = $this->createForm(MangaFilterType::class, null, [
			'genres' => array_combine(array_column($mangaGenres, 'name'), array_column($mangaGenres, 'name')),
		]);
		$filterForm->handleRequest($request);

		// Get filter data from form
		$filterData = [];

		// Check if the form is submitted and valid
		if ($filterForm->isSubmitted() && $filterForm->isValid()) {
			// Get the filter data from the form
			$filterData = $filterForm->getData();
		}
		
		// Retrieve the current page number from the query parameters, defaulting to 1 if not present
		$page = $request->query->getInt('page', 1);
		
		if (!empty($filterData)) {
			
			// Get paginated results based on filter data
			$filteredResults = $this->mangaRepository->getFilteredMangas($filterData);

			// Set flash messages based on which filters were found
			if (!$filteredResults['results']['typeExists'] && !empty($filterData['type'])) {
				$this->addFlash('warning', 'Aucun manga trouvé pour le type sélectionné.');
			}
			if (!$filteredResults['results']['genreExists'] && !empty($filterData['genre'])) {
				$this->addFlash('warning', 'Aucun manga trouvé pour le ou les genre(s) sélectionné(s).');
			}
			if (!$filteredResults['results']['authorExists'] && !empty($filterData['author'])) {
				$this->addFlash('warning', 'Aucun manga trouvé pour l\'auteur-autrice sélectionné·e.');
			 
				// Check if the list of mangas is empty
			} elseif (!empty($filteredResults['mangasList'])) {
				$mangas = $this->customPaginator->paginate($filteredResults['mangasList'], $page, 16);
			} else {
				// Get all mangas and paginate them
				$mangasList = $this->mangaRepository->findAll();
				$mangas = $this->customPaginator->paginate($mangasList, $page, 16);		

				return $this->render('/manga/index.html.twig', [
					'mangas' => $mangas,
					'filterForm' => $filterForm,
					'currentPage' => $page,
					'mangaTypes' => $mangaTypes,
					'mangaGenres' => $mangaGenres,
				]);
			}
		} else {
			// Get all mangas and paginate them
			$mangasList = $this->mangaRepository->findAll();
			$mangas = $this->customPaginator->paginate($mangasList, $page, 16);		
		}
		return $this->render('/manga/index.html.twig', [
			'mangas' => $mangas,
			'filterForm' => $filterForm,
			'currentPage' => $page,
			'mangaTypes' => $mangaTypes,
			'mangaGenres' => $mangaGenres,
		]);
	}

	#[Route('/{id}', name: 'app_manga_display', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
	public function display(?Manga $manga, Request $request, EntityManagerInterface $entityManager): Response
	{
		// Get currently connected user's manga rating if exist
		$user = $this->getUser();
		$rating = $this->ratingRepository->findOneBy([
				'user' => $user,
				'manga' => $manga]);
		$review = $this->reviewRepository->findOneBy([
				'user' => $user,
				'manga' => $manga]);

		// Create the forms
		$ratingForm = $this->createForm(RatingType::class, $rating ?: new Rating());
		$reviewForm = $this->createForm(ReviewType::class, $review ?: new Review());

		// Handle form submission
		$ratingForm->handleRequest($request);
		$reviewForm->handleRequest($request);
			
		if ($ratingForm->isSubmitted() && $ratingForm->isValid()) { 
			if (!$rating) {
				$rating = new Rating();
				$rating->setUser($user);
				$rating->setManga($manga);
			}

			// Update or create rating and save to database
			$rating->setUser($user);
			$rating->setManga($manga);
			$rating->setNote($ratingForm->get('note')->getData());
			$entityManager->persist($rating);
			$entityManager->flush();
			$this->addFlash('success', 'Votre note a été ajoutée.');
			return $this->redirectToRoute('app_manga_display', ['id' => $manga->getId()]);
			}
					
		
		if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
			if (!$review) {
				$review = new Review();
				$review->setUser($user);
				$review->setManga($manga);
			}
			$review->setUser($user);
			$review->setManga($manga);
			$review->setContent($reviewForm->get('content')->getData());
			$updatedAt = new \DateTimeImmutable();
			$review->setUpdatedAt($updatedAt);

			$entityManager->persist($review);
			$entityManager->flush();
			$this->addFlash('success', 'Votre critique a été ajoutée.');
			return $this->redirectToRoute('app_manga_display', ['id' => $manga->getId()]);
		}
		return $this->render('/manga/display.html.twig', [
			'manga' => $manga,
			'ratingForm' => $ratingForm,
			'reviewForm' => $reviewForm,
			'rating' => $rating ? $rating : null,
			'review' => $review ? $review : null,
			'currentUser' => $user,
		]);
	}

	#[Route('/review/{id}/delete', name: 'app_review_delete', methods: ['POST'])]
	public function deleteReview(Request $request, Review $review, EntityManagerInterface $entityManager) : Response
	{
		if ($this->isCsrfTokenValid('delete' . $review->getId(), $request->request->get('_token'))) {
			$entityManager->remove($review);
			$entityManager->flush();
			$this->addFlash('success', 'Votre critique a été supprimée.');
		}
	return $this->redirectToRoute('app_manga_display', ['id' => $review->getManga()->getId()]);
	}
	
	#[Route('/rating/{id}/delete', name: 'app_rating_delete', methods: ['POST'])]
	public function delete(Request $request, Rating $rating, EntityManagerInterface $entityManager) : Response
	{
		if ($this->isCsrfTokenValid('delete' . $rating->getId(), $request->request->get('_token'))) {
			$entityManager->remove($rating);
			$entityManager->flush();
			$this->addFlash('success', 'Votre note a été supprimée.');
		}
	return $this->redirectToRoute('app_manga_display', ['id' => $rating->getManga()->getId()]);
	}
}