<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToMany;
use ApiPlatform\Core\Annotation\ApiResource;
use phpDocumentor\Reflection\Types\True_;


#[Entity]
#[ApiResource]
class Game implements \JsonSerializable {

    #[Column(unique:true)] #[Id] #[GeneratedValue] private int $id;
    #[Column(name:'date')] public \DateTime $dateTime;
    #[Column] public float $score;

    // optional string for api used in this game
    #[Column(nullable:true)] public string $api = '';

    //optional column for color of closed cards in this game
    #[Column(nullable:true)] public string $color_closed = '';

    //optional column for color of found cards in this game
    #[Column(nullable:true)] public string $color_found = '';

    // Zo doe je dus unidirectionele OneToMany in doctrine...
    // zie
    // https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/association-mapping.html#one-to-many-unidirectional-with-join-table

    #[ManyToMany(targetEntity: Player::class, mappedBy: "games")]
    private Collection $player;

    public function __construct(Player $player, mixed $params)
    {
        $this->player = new ArrayCollection();
        $this->player[] = $player;
        $this->dateTime = new \DateTime('now');
        $this->score = $params['score'];
        $this->api = $params['api'] ?? '';
        $this->color_found = $params['color_found'] ?? '';
        $this->color_closed = $params['color_closed'] ?? '';
    }

    public function getId(): int { return $this->id; }

    public function getDayFromDate():string {
        return $this->dateTime->format('Y-m-d');
    }

    public function jsonSerialize():mixed {
        return array(
            'date' => $this->dateTime,
            'day' => $this->getDayFromDate(),
            'score' => $this->score,
            'api' => $this->api,
            'color_closed' => $this->color_closed,
            'color_found' => $this->color_found
        );
    }
}
