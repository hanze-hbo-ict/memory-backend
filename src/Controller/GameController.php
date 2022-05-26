<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Game;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Json;
use Twig\Error\Error;

#[Route("/game")]
class GameController extends AbstractController {
    #[Route('/')]
    public function index():Response {
        return new Response("GameController");
    }

    #[Route('/all',methods:['GET'])]
    public function getAllGames(ManagerRegistry $doctrine):Response {
        $em = $doctrine->getManager();
        $game_repo = $em->getRepository(Game::class);
        return new JsonResponse($game_repo->findAll());
    }

    #[Route("/{id}", requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getGame($id, ManagerRegistry $doctrine):Response {
        $em = $doctrine->getManager();
        $game = $em->find(Game::class, $id);
        if ($game) return new JsonResponse($game);
        else return new Response('', 404);
    }


    #[Route('/save', methods:['POST'])]
    public function saveGame(ManagerRegistry $doctrine):Response {
        set_error_handler(fn() => throw new \ErrorException());
        try {
            $params = json_decode(Request::createFromGlobals()->getContent(), true);
            $em = $doctrine->getManager();
            $player = $em->find(Player::class, $params['id']);

            $player->addGame(new Game($player, $params));
            $em->persist($player);
            $em->flush();
            return new JsonResponse($player);
        } catch (\ErrorException $e) {
            return new Response('', 400);
        }
    }
}
