<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Game;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Json;
use Twig\Error\Error;

#[Route("/game")]
#[IsGranted('ROLE_USER')]
class GameController extends AbstractController 
{
    private $player = null;
    public function __construct(private readonly Security $security) {

        $this->player = $this->security->getUser();
    }

    #[Route('/')]
    public function index():Response {
        return new Response("GameController");
    }

    #[Route('/all')]
    public function getAllGames():Response {
        $resp = [];
        foreach ($this->player->getGames() as $game) {
            $resp[] = $game->jsonSerialize();
        };
        return new JsonResponse($resp);
    }

    #[Route("/{id}", requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getGame($id, ManagerRegistry $doctrine):Response {
        $player_games = $this->player->getGames();
        $em = $doctrine->getManager();
        $game = $em->find(Game::class, $id);

        if ($game) {
            if ($player_games->contains($game)) return new JsonResponse($game);
            else return new Response('', Response::HTTP_UNAUTHORIZED);
        } else return new Response('', Response::HTTP_NOT_FOUND);
    }


    #[Route('/save', methods:['POST'])]
    public function saveGame(ManagerRegistry $doctrine):Response {
        // set_error_handler(fn() => throw new \ErrorException());
        try {
            $params = json_decode(Request::createFromGlobals()->getContent(), true);
            $em = $doctrine->getManager();
//            $player = $em->find(Player::class, $params['id']);

            $this->player->addGame(new Game($this->player, $params));
            $em->persist($this->player);
            $em->flush();
            return new JsonResponse($this->player);
        } catch (\ErrorException $e) {
            return new Response('', 400);
        }
    }
}
