<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin')]
class AdminController extends AbstractController
{
    #[Route('/aggregate', methods: ['GET'])]
    public function aggregateData(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $aggregate = [];
        $aggregate[] = $em->createQuery("select count(g) as aantal_spellen from App\Entity\Game g")->getArrayResult()[0];
        $aggregate[] = $em->createQuery("select count(p) as aantal_spelers from App\Entity\Player p")->getArrayResult()[0];
        $aggregate[] = $em->createQuery("select g.api, count(g.api) as aantal from App\Entity\Game g group by g.api")->getResult();

        return new JsonResponse($aggregate);
    }


    #[Route('/players', methods: ['GET'])]
    public function players(ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $players = $em->createQuery("select p.username, p.email from App\Entity\Player p")->getArrayResult();
        return new JsonResponse($players);
    }
}