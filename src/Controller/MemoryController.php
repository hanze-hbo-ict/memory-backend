<?php

namespace App\Controller;

use App\Entity\Player;
use Doctrine\Persistence\ManagerRegistry;
use MongoDB\Driver\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/memory')]
class MemoryController extends AbstractController
{
    #[Route('/')]
    public function index():Response{
        return new Response('MemoryController');
    }

    #[Route('/login', methods:['POST'])]
    public function login():Response {
        return new Response('');
    }

    #[Route('/scores', methods: ['GET'])]
    public function scores(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $scores = $em->createQuery("select p.username, avg(g.score) as score from App\Entity\Player p 
                    join p.games g group by p.username")->getArrayResult();
        return new JsonResponse($scores);
    }

    #[Route('/top-scores', methods:['GET'])]
    public function topScores(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $scores = $em->createQuery("select p.username, min(g.score) as score from App\Entity\Player p 
                    join p.games g group by p.username order by score")->getArrayResult();
        return new JsonResponse($scores);

    }

    #[Route('/register', methods: ['POST'])]
    public function register(ManagerRegistry $doctrine): Response {
        try {
            $params = json_decode(Request::createFromGlobals()->getContent(), true);
            $pw = password_hash($params['password'], PASSWORD_DEFAULT);
            $player = new Player($params['username'], $params['email'], $pw);
            $em = $doctrine->getManager();
            $em->persist($player);
            $em->flush();
            return new Response("", 201, ["Location" => "/player/$player->id"]);
        } catch (\ErrorException $e) {
            ob_start();
            echo $e->getMessage();
            echo "\n\n";
            echo $e->getTraceAsString();
            return new Response(ob_get_clean(),400);
        }
    }

}