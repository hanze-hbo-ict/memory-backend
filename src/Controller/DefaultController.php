<?php

namespace App\Controller;

use App\Entity\Player;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends AbstractController {
    #[Route('/', methods:['GET'])]
    public function index():Response {
        return new Response('DefaultController');
    }

    #[Route('/api/login_check', methods:['POST'])]
    public function login():Response {
        return new Response('');
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