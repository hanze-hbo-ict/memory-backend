<?php

namespace App\Controller;

use App\Entity\Player;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route("/api/player/")]
class PlayerController extends AbstractController {

    public function __construct(private LoggerInterface $logger) { }

    #[Route('{id}', requirements:['id'=>'\d+'])]
    public function getUserData($id, ManagerRegistry $doctrine):Response {
        $em = $doctrine->getManager();
        $user = $em->find(Player::class, $id);
        if ($user) return new JsonResponse($user);
        else return new Response('', 404);
    }

    #[Route('/')]
    public function index():Response {
        return new Response("PlayerController");
    }

    #[Route('{id}/games',  requirements:['id'=>'\d+'], methods:['GET'])]
    public function getPlayerGames($id, ManagerRegistry $doctrine):Response {
        $em = $doctrine->getManager();
        $user = $em->find(Player::class, $id);
        if ($user) return new JsonResponse($user->getGames()->toArray());
        else return new Response('', 404);
    }

    #[Route('{id}/preferences', requirements:['id'=>'\d+'], methods:['GET', 'POST'])]
    public function getPlayerPreferences($id, ManagerRegistry $doctrine):Response {
        $em = $doctrine->getManager();
        $user = $em->find(Player::class, $id);
        if ($user) {
            $request = Request::createFromGlobals();
            if ($request->getMethod() == 'POST') {
                $params = json_decode(Request::createFromGlobals()->getContent(), true);
                $user->setPreferences($params);
                $em->persist($user);
                $em->flush();
                return new JsonResponse('',204);
            } else return new JsonResponse($user->getPreferences());
        }

        return new Response('', 404);
    }

    #[Route('{id}/email', requirements:['id'=>'\d+'],  methods:['GET', 'PUT'])]
    public function playerEmail($id, ManagerRegistry $doctrine):Response {
        $em = $doctrine->getManager();
        $user = $em->find(Player::class, $id);
        if ($user) {
            $request = Request::createFromGlobals();
            if ($request->getMethod() == 'PUT') {
                $params = json_decode(Request::createFromGlobals()->getContent(), true);
                $user->email = $params['email'];
                $em->persist($user);
                $em->flush();
                return new JsonResponse('', 204);
            } else return new JsonResponse($user->email);
        }

        return new Response('', 404);    }


}