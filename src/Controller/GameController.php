<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Game;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route("/game")]
class GameController extends AbstractController {
    #[Route('/')]
    public function index():Response {
        return new Response("GameController");
    }

    #[Route("/{id}", requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getGame($id, ManagerRegistry $doctrine):Response {
        $em = $doctrine->getManager();
        $game = $em->find(Game::class, $id);
        return new JsonResponse($game);
    }

    #[Route('/save', methods:['POST'])]
    public function saveGame(ManagerRegistry $doctrine):Response {
        $params = json_decode(Request::createFromGlobals()->getContent(), true);
        $em = $doctrine->getManager();
        $user = $em->find(User::class, $params['id']);

        $user->addGame(new Game($user, $params['score']));
        $em->persist($user);
        $em->flush();
        return new JsonResponse($user);
    }


}
