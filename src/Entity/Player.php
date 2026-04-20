<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use App\Repository\PlayerRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ApiResource]
class Player implements \JsonSerializable, UserInterface, PasswordAuthenticatedUserInterface 
{
    
    #[Column(unique:true)] #[Id] #[GeneratedValue] public int $id;
    #[Column(name:'name')] public string $username;
    #[Column] public string $email;

    #[ORM\Column(nullable: true)]
    private ?string $password = null;
    #[Column(nullable:true)] public string $preferred_api = '';
    #[Column(nullable:true)] public string $preferred_color_closed = '';
    #[Column(nullable:true)] public string $preferred_color_found = '';

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: true)]
    private ?string $githubId = null;

    #[ORM\OneToOne(mappedBy: 'player', targetEntity: PlayerAvatar::class,
        cascade: ['persist', 'remove'])]
    public ?PlayerAvatar $avatar = null;

    #[ManyToMany(targetEntity:Game::class, cascade: ["persist"])]
    #[JoinTable(name:"player_games")]
    #[JoinColumn(name: "player_id", referencedColumnName: "id")]
    #[InverseJoinColumn(name: "game_id", referencedColumnName: "id", unique:true)]
    private $games;

    public function __construct(string $username, string $email, string $password = '')
    {
        $this->username = $username;
        $this->email = $email;
        if (!empty($password)) $this->password = $password;
        $this->games = new ArrayCollection();
    }

    public function setPasswordHash(string $pw): void {
        $this->password = $pw;
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
            $t->add($g); //->jsonSerialize());
        }
        return $t;
    }

    public function jsonSerialize():mixed {
        $games = $this->getGames()->toArray();
        return array(
            'id' => $this->id,
            'name' => $this->username,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'games' => $games
        );
    }

    public function getPassword(): ?string
    {
        return $this->password_hash ?? '';
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        if ($this->username=='Henk') $roles[] = 'ROLE_ADMIN';
        return $roles;
    }

    public function getId():int{
        return $this->id;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->id;
    }
}