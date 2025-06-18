<?php

namespace App\Controller;

use App\Entity\Player;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route("/player")]
#[IsGranted('ROLE_USER')]
class PlayerController extends AbstractController {

    private $user_id = null;
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly Security $security
    ) {
        $this->user_id = $this->security->getUser()->getUserIdentifier();
    }

    #[Route('/')]
    public function getUserData(ManagerRegistry $doctrine):Response {
        if(!$this->userIsValid($this->user_id)) return new Response('', 403);

        $em = $doctrine->getManager();
        $user = $em->find(Player::class, $this->user_id);
        if ($user) return new JsonResponse($user);
        else return new Response('', 404);
    }


    #[Route('/games',  methods:['GET'])]
    public function getPlayerGames(ManagerRegistry $doctrine):Response {
        if(!$this->userIsValid($this->user_id)) return new Response('', 403);

        $em = $doctrine->getManager();
        $user = $em->find(Player::class, $this->user_id);
        if ($user) return new JsonResponse($user->getGames()->toArray());
        else return new Response('', 404);
    }

    #[Route('/preferences', methods:['GET', 'POST'])]
    public function playerPreferences(ManagerRegistry $doctrine):Response {
        if(!$this->userIsValid()) return new Response('', 403);

        $em = $doctrine->getManager();
        $user = $em->find(Player::class, $this->user_id);
        if ($user) {
            $request = Request::createFromGlobals();
            if ($request->getMethod() == 'POST') {
                $params = json_decode(Request::createFromGlobals()->getContent(), true);
                $user->setPreferences($params);
                $em->persist($user);
                $em->flush();
                return new JsonResponse('', 204);
            } else return new JsonResponse($user->getPreferences());
        }

        return new Response('', 404);
    }

    #[Route('/email', methods:['GET', 'PUT'])]
    public function playerEmail(ManagerRegistry $doctrine):Response {
        if(!$this->userIsValid()) return new Response('', 403);

        $em = $doctrine->getManager();
        $user = $em->find(Player::class, $this->user_id);
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

        return new Response('', 404);
    }


    private function userIsValid():bool {
        $user = $this->security->getUser();
        return (in_array('ROLE_USER', $user->getRoles())
            || in_array('ROLE_ADMIN', $user->getRoles()));
    }


}