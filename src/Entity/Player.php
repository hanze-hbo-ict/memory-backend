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
    #[Column(nullable:true)] public string $preferred_api = '';
    #[Column(nullable:true)] public string $preferred_color_closed = '';
    #[Column(nullable:true)] public string $preferred_color_found = '';

    #[ManyToMany(targetEntity:Game::class, cascade: ["persist"])]
    #[JoinTable(name:"player_games")]
    #[JoinColumn(name: "player_id", referencedColumnName: "id")]
    #[InverseJoinColumn(name: "game_id", referencedColumnName: "id", unique:true)]
    private $games;

    public function __construct(string $username, string $email, string $password_hash)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->games = new ArrayCollection();
    }

    public function addGame(Game $game) {
        $this->games[] = $game;
    }

    public function getPreferences():array {
        return [
            'preferred_api' => $this->preferred_api,
            'color_closed' => $this->preferred_color_closed,
            'color_found' => $this->preferred_color_found
        ];
    }

    public function setPreferences(array $params) {
        $this->preferred_api = $params['api'];
        $this->preferred_color_found = $params['color_found'];
        $this->preferred_color_closed = $params['color_closed'];
    }

    public function getGames():Collection {
        $t = new ArrayCollection();
        foreach($this->games as $g) {
            $t->add($g->jsonSerialize());
        }
        return $t;
    }

    public function jsonSerialize():mixed {
        $games = $this->getGames()->toArray();
        return array(
            'id' => $this->id,
            'name' => $this->username,
            'email' => $this->email,
            'games' => $games
        );
    }

    public function getPassword(): ?string
    {
        return $this->password_hash;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        if ($this->username=='Henk') $roles[] = 'ROLE_ADMIN';
        return $roles;
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