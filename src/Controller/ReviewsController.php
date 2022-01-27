<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReviewsController extends AbstractController
{

    public function __construct(
        private ReviewRepository $reviewRepository,
        private ValidatorInterface $validator
    ) {
    }

    #[Route('/api/reviews', name: 'reviews.list', methods: 'GET')]
    public function list(): Response
    {
        return $this->json([
            'message' => 'GET Method',
            'path' => 'src/Controller/ReviewsController.php',
        ]);
    }

    #[Route('/api/reviews', name: 'reviews.store', methods: 'POST')]
    public function store(Request $request): Response
    {
        $review = Review::fromArray($request->query->all());
        $errors = $this->validator->validate($review);

        if (count($errors) > 0) {
            return new Response($errors, 400);
        }

        $this->reviewRepository->save($review);

        return $this->json([
            'message' => 'Review successfully saved',
            'review' => $review->toArray(),
        ]);
    }
}
