<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Player>
 */
class PlayerRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function countAll(): int
    {
        return (int) $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return array<array{username: string, email: string}>
     */
    public function findAllUsernamesAndEmails(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.username, p.email')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<array{username: string, score: float}>
     */
    public function findAverageScores(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.username, AVG(g.score) as score')
            ->join('p.games', 'g')
            ->groupBy('p.username')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<array{username: string, score: float}>
     */
    public function findTopScores(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.username, MIN(g.score) as score')
            ->join('p.games', 'g')
            ->groupBy('p.username')
            ->orderBy('score', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function save(Player $player, bool $flush = true): void
    {
        $this->getEntityManager()->persist($player);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}