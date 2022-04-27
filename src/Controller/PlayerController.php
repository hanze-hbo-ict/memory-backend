<?php

namespace App\Controller;

use App\Entity\Player;

use Doctrine\Persistence\ManagerRegistry;
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

    #[Route('/{id}', methods:['GET'])]
    public function getPlayerById($id, ManagerRegistry $doctrine):Response {
        $em = $doctrine->getManager();
        $user = $em->find(Player::class, $id);
        return new JsonResponse($user);
    }

    #[Route('/login')]
    public function login(ManagerRegistry $doctrine):Response {
        $params = json_decode(Request::createFromGlobals()->getContent(), true);
        $em = $doctrine->getManager();
        $user = $em->getRepository(Player::class)->findOneBy(['name'=>$params['name']]);
        if (password_verify($params['password'], $user->getPasswordHash())) {
            return new JsonResponse($user);
        } else {
            return new Response('no way');
        }
    }

    #[Route('/register', methods: ['POST'])]
    public function register(ManagerRegistry $doctrine): Response {
        $params = json_decode(Request::createFromGlobals()->getContent(), true);
        $pw = password_hash($params['password'], PASSWORD_DEFAULT);
        $player = new Player($params['name'], $params['email'], $pw);
        $em = $doctrine->getManager();
        $em->persist($player);
        $em->flush();
        return new Response($player->getId());
    }
}