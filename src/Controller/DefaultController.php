<?php

namespace App\Controller;

use App\Entity\Player;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends AbstractController {
    #[Route('/', methods:['GET'])]
    public function index(LoggerInterface $logger):Response {
        return new Response('Hallo allemaal 👋');
    }

    #[Route('/frontend', methods:['GET'])]
    public function demo():Response {
        $rv['message'] = 'Welkom bij de memory backend api.';
        $rv['date'] = date("F j, Y, g:i a");
        return new JsonResponse($rv);
    }
}
