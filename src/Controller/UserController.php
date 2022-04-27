<?php

namespace App\Controller;

use App\Entity\User;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route("/user")]
class UserController extends AbstractController {
    #[Route('/')]
    public function index():Response {
        return new Response("UserController");
    }

    #[Route('/{id}', methods:['GET'])]
    public function getUserById($id, ManagerRegistry $doctrine):Response {
        $em = $doctrine->getManager();
        $user = $em->find(User::class, $id);
        return new JsonResponse($user);
    }

    #[Route('/login')]
    public function login(ManagerRegistry $doctrine):Response {
        $params = json_decode(Request::createFromGlobals()->getContent(), true);
        $em = $doctrine->getManager();
        $user = $em->getRepository("App\Entity\User")->findOneBy(['name'=>$params['name']]);
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
        $user = new User($params['name'], $params['email'], $pw);
        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();
        return new Response($user->getId());
    }
}