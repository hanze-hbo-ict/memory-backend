<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/memory')]
class MemoryController extends AbstractController
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
    ) {}

    #[Route('/')]
    public function index(): Response
    {
        return new Response('MemoryController');
    }

    #[Route('/login', methods: ['POST'])]
    public function login(): Response
    {
        return new Response('');
    }

    #[Route('/scores', methods: ['GET'])]
    public function scores(): JsonResponse
    {
        return new JsonResponse($this->playerRepository->findAverageScores());
    }

    #[Route('/top-scores', methods: ['GET'])]
    public function topScores(): JsonResponse
    {
        return new JsonResponse($this->playerRepository->findTopScores());
    }

    #[Route('/register', methods: ['POST'])]
    public function register(Request $request): Response
    {
        try {
            $params = json_decode($request->getContent(), true);
            $passwordHash = password_hash($params['password'], PASSWORD_DEFAULT);
            $player = new Player($params['username'], $params['email'], $passwordHash);

            $this->playerRepository->save($player);

            return new Response('', Response::HTTP_CREATED, [
                'Location' => "/player/{$player->id}"
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
