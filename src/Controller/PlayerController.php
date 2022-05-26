<?php

namespace App\Controller;

use App\Entity\Player;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route("/player/{id}", requirements:['id'=>'\d+'])]
class PlayerController extends AbstractController {

    #[Route('')]
    public function test():Response {
        return new Response("hallo");
    }

    #[Route('/')]
    public function index():Response {
        return new Response("PlayerController");
    }

    #[Route('/data', methods:['GET'])]
    public function getPlayerData($id, ManagerRegistry $doctrine):Response {
        return new JsonResponse('/user/id/data');
    }

    #[Route('/games', methods:['GET'])]
    public function getPlayerGames($id, ManagerRegistery $doctrine):Response {
        return new JsonResponse('/user/id/games');
    }

    #[Route('/preferences', methods:['GET', 'POST', 'PUT'])]
    public function playerPreferences($id, ManagerRegister $doctrine):Response {
        return new JsonResponse('/user/id/prefs');
    }

    #[Route('/email', methods:['GET', 'PUT'])]
    public function playerEmail($id, ManagerRegistry $doctrine):Response {
        return new JsonResponse('/user/id/email');
    }


}