<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin')]
class AdminController extends AbstractController
{
    public function __construct(private LoggerInterface $logger) {}
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

    /*
     * Onderstaande endpoint geeft het aantal spelen dat per dag gespeeld is terug
     * Feitelijk is dit gewoon een `group by`, maar dat kregen we niet aan de praat
     * in DBAL. Dus we hebben we het maar gewoon met een loopje gemaakt.
     *
     * Iemand een betere oplossing hiervoor heeft, is welkom om een PR te doen. Als -ie
     * goed is, krijg je een snikker.
     *
     * BABA/HOEM
     */

    #[Route('/dates', methods: ['GET'])]
    public function getAggregatedByDate(ManagerRegistry $doctrine) {
        $em = $doctrine->getManager();
        $games = $em->createQuery("select g.dateTime as date from App\Entity\Game g order by date")->getArrayResult();

        $cnt = [];
        foreach ($games as $el) {
            $key = $el['date']->format('Y-m-d');
            $cnt[$key] = array_key_exists($key, $cnt) ?  $cnt[$key]+1 : 1;
        }

        return new JsonResponse($cnt);
    }



}