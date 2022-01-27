<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewsController extends AbstractController
{

    public function __construct(private ReviewRepository $reviewRepository)
    {
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
        return $this->json([
            'message' => 'POST Method',
            'path' => 'src/Controller/ReviewsController.php',
        ]);
    }
}
