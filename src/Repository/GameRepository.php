<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function countAll(): int
    {
        return (int) $this->createQueryBuilder('g')
            ->select('COUNT(g.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return array<array{api: string|null, aantal: int}>
     */
    public function countByApi(): array
    {
        return $this->createQueryBuilder('g')
            ->select('g.api, COUNT(g.api) as aantal')
            ->groupBy('g.api')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<string, int>
     */
    public function countByDate(): array
    {
        // Use native SQL for DATE() function (SQLite compatible)
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT DATE(date) as game_date, COUNT(*) as count
                FROM games
                GROUP BY DATE(date)
                ORDER BY game_date";

        $results = $conn->executeQuery($sql)->fetchAllAssociative();

        $aggregated = [];
        foreach ($results as $row) {
            $aggregated[$row['game_date']] = (int) $row['count'];
        }

        return $aggregated;
    }
}