<?php

namespace App\Controller;

use App\Entity\Player;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use MongoDB\Driver\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route("/player")]
class PlayerController extends AbstractController {
    #[Route('/')]
    public function index():Response {
        return new Response("PlayerController");
    }

    #[Route('/all', methods:['GET'])]
    public function getAllPlayers(ManagerRegistry $doctrine):Response {
        $em = $doctrine->getManager();
        $user_repo = $em->getRepository(Player::class);
        return new JsonResponse($user_repo->findAll());
    }

    #[Route('/{id}',  requirements: ['id' => '\d+'], methods:['GET'])]
    public function getPlayerById($id, ManagerRegistry $doctrine):Response {
        $em = $doctrine->getManager();
        $user = $em->find(Player::class, $id);
        if ($user) return new JsonResponse($user);
        else return new Response('', 404);
    }

    #[Route('/test')]
    public function test():Response {
        $hash = password_hash("totempaal", PASSWORD_DEFAULT);
        $user = new Player('Charles', 'charles@charles', $hash);
        $token = $this->get('lexik_jwt_authentication.jwt_manager')->create($user);
        return new JsonResponse(['token' => $token]);
    }

    #[Route('/login')]
    public function login(ManagerRegistry $doctrine):Response {
        set_error_handler(fn() => throw new \ErrorException());

        try {
            $params = json_decode(Request::createFromGlobals()->getContent(), true);
            $em = $doctrine->getManager();
            $player = $em->getRepository(Player::class)->findOneBy(['name' => $params['name']]);
            if (password_verify($params['password'], $player->password_hash)) {
                return new JsonResponse($player);
            } else {
                return new Response("Bad Credentials", 401);
            }
        } catch (\ErrorException $e) {
            return new Response("",400);
        }
    }

    #[Route('/register', methods: ['POST'])]
    public function register(ManagerRegistry $doctrine): Response {
        set_error_handler(fn() => throw new \ErrorException());

        try {
            $params = json_decode(Request::createFromGlobals()->getContent(), true);
            $pw = password_hash($params['password'], PASSWORD_DEFAULT);
            $player = new Player($params['name'], $params['email'], $pw);
            $em = $doctrine->getManager();
            $em->persist($player);
            $em->flush();
            return new Response("", 201, ["Location" => "/player/$player->id"]);
        } catch (\ErrorException $e) {
            return new Response("",400);
        }
    }
}