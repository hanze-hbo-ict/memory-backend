<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PlayerAvatar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'avatar', targetEntity: Player::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Player $player;

    #[ORM\Column(type: 'blob')]
    private $data;

    #[ORM\Column(length: 50)]
    private string $mimeType;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function setAvatar(string $binary): void
    {
        $this->data = $binary;
    }

    public function getAvatar(): string
    {
        if (is_resource($this->data)) {
            return stream_get_contents($this->data);
        }
        return $this->data;
    }

    public function setMimeType(string $mimeType): void {
        $this->mimeType = $mimeType;
    }

    public function getMimeType(): string {
        return $this->mimeType;
    }



}