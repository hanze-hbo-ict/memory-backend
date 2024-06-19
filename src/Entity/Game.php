<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity]
#[ApiResource]
class Game implements \JsonSerializable {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    private int $id;

    #[ORM\Column(type: 'datetime')]
    public \DateTime $dateTime;

    #[ORM\Column(type: 'float')]
    public float $score;

    #[ORM\Column(type: 'string', nullable: true)]
    public string $api = '';

    #[ORM\Column(type: 'string', nullable: true)]
    public string $color_closed = '';

    #[ORM\Column(type: 'string', nullable: true)]
    public string $color_found = '';

    #[ORM\ManyToMany(targetEntity: Player::class, mappedBy: 'games')]
    private Collection $players;

    public function __construct(array $params)
    {
        $this->players = new ArrayCollection();
        $this->dateTime = new \DateTime('now');
        $this->score = $params['score'];
        $this->api = $params['api'] ?? '';
        $this->color_found = $params['color_found'] ?? '';
        $this->color_closed = $params['color_closed'] ?? '';
    }

    public function getId(): int { return $this->id; }

    public function getDayFromDate(): string {
        return $this->dateTime->format('Y-m-d');
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->addGame($this); // Ensure bidirectional relationship
        }

        return $this;
    }

    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function jsonSerialize(): mixed {
        return [
            'date' => $this->dateTime,
            'day' => $this->getDayFromDate(),
            'score' => $this->score,
            'api' => $this->api,
            'color_closed' => $this->color_closed,
            'color_found' => $this->color_found
        ];
    }
}
