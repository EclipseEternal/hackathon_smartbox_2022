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
        $list = $this->reviewRepository->findBy([], ['id' => 'DESC']);

        return $this->json(array_map(static function(Review $review): array {
            return $review->toArray();
        }, $list));
    }

    #[Route('/api/reviews', name: 'reviews.store', methods: 'POST')]
    public function store(Request $request): Response
    {
        $review = Review::fromArray(json_decode($request->getContent(), true));
        $errors = $this->validator->validate($review);

        if (count($errors) > 0) {

            $errorsArr = [];

            foreach ($errors as $violation) {
                $errorsArr[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return $this->json($errorsArr, 400);
        }

        $this->reviewRepository->save($review);

        return $this->json([
            'message' => 'Review successfully saved',
            'review' => $review->toArray(),
        ]);
    }
}
