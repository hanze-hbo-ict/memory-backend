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


#[Entity]
#[ApiResource]
class Game implements \JsonSerializable {

    #[Column(unique:true)] #[Id] #[GeneratedValue] private int $id;
    #[Column] public \DateTime $date;
    #[Column] public float $score;

    // Zo doe je dus unidirectionele OneToMany in doctrine...
    // zie
    // https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/association-mapping.html#one-to-many-unidirectional-with-join-table

    #[ManyToMany(targetEntity: Player::class, mappedBy: "games")]
    private Collection $player;

    public function __construct(Player $player, float $score)
    {
        $this->player = new ArrayCollection();
        $this->player[] = $player;
        $this->date = new \DateTime('now');
        $this->score = $score;
    }

    public function getId(): int { return $this->id; }

    public function jsonSerialize():mixed {
        return array(
            'date' => $this->date,
            'score' => $this->score,
        );
    }
}
