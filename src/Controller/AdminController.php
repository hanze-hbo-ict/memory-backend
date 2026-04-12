<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController 
{
    public function __construct(
        private readonly GameRepository $gameRepository,
        private readonly PlayerRepository $playerRepository,
    ) {}

    #[Route('/aggregate', methods: ['GET'])]
    public function aggregateData(): JsonResponse
    {
        return new JsonResponse([
            ['aantal_spellen' => $this->gameRepository->countAll()],
            ['aantal_spelers' => $this->playerRepository->countAll()],
            $this->gameRepository->countByApi(),
        ]);
    }

    #[Route('/dates', methods: ['GET'])]
    public function getAggregatedByDate(): JsonResponse
    {
        return new JsonResponse($this->gameRepository->countByDate());
    }

    #[Route('/players', methods: ['GET'])]
    public function players(): JsonResponse
    {
        return new JsonResponse($this->playerRepository->findAllUsernamesAndEmails());
    }

    #[Route('/games',methods:['GET'])]
    public function getAllGames(ManagerRegistry $doctrine):Response {
        $em = $doctrine->getManager();
        $game_repo = $em->getRepository(Game::class);
        return new JsonResponse($game_repo->findAll());
    }
}