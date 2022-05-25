<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[Entity]
#[ApiResource]
class Player implements \JsonSerializable, UserInterface, PasswordAuthenticatedUserInterface {
    #[Column(unique:true)] #[Id] #[GeneratedValue] public int $id;
    #[Column(name:'name')] public string $username;
    #[Column] public string $email;
    #[Column] public string $password_hash;

    #[ManyToMany(targetEntity:Game::class, cascade: ["persist"])]
    #[JoinTable(name:"player_games")]
    #[JoinColumn(name: "player_id", referencedColumnName: "id")]
    #[InverseJoinColumn(name: "game_id", referencedColumnName: "id", unique:true)]
    private $games;

    public function __construct(string $name, string $email, string $password_hash)
    {
        $this->username = $name;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->games = new ArrayCollection();
    }

    public function addGame(Game $game) {
        $this->games[] = $game;
    }

    public function getGames():Collection {
        return $this->games;
    }

    public function jsonSerialize():mixed {
        $t = [];
        foreach($this->games as $g) {
            $t[] = $g->jsonSerialize();
        }
        return array(
            'id' => $this->id,
            'name' => $this->username,
            'email' => $this->email,
            'games' => $t
        );
    }

    public function getPassword(): ?string
    {
        return $this->password_hash;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}