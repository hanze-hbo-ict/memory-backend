<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ApiResource]
class Player implements \JsonSerializable, UserInterface, PasswordAuthenticatedUserInterface {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', unique: true)]
    public int $id;

    #[ORM\Column(type: 'string')]
    public string $username;

    #[ORM\Column(type: 'string')]
    public string $email;

    #[ORM\Column(type: 'string')]
    public string $password_hash;

    #[ORM\Column(type: 'string', nullable: true)]
    public string $preferred_api = '';

    #[ORM\Column(type: 'string', nullable: true)]
    public string $preferred_color_closed = '';

    #[ORM\Column(type: 'string', nullable: true)]
    public string $preferred_color_found = '';

    #[ORM\ManyToMany(targetEntity: Game::class, inversedBy: 'players', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'player_games')]
    #[ORM\JoinColumn(name: 'player_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'game_id', referencedColumnName: 'id')]
    private Collection $games;

    public function __construct(string $username, string $email, string $password_hash)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->games = new ArrayCollection();
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->addPlayer($this); // Ensure bidirectional relationship
        }

        return $this;
    }

    public function getGames(): Collection
    {
        return $this->games;
    }

    public function getPreferences(): array
    {
        return [
            'preferred_api' => $this->preferred_api,
            'color_closed' => $this->preferred_color_closed,
            'color_found' => $this->preferred_color_found
        ];
    }

    public function setPreferences(array $params): void
    {
        $this->preferred_api = $params['api'];
        $this->preferred_color_found = $params['color_found'];
        $this->preferred_color_closed = $params['color_closed'];
    }

    public function jsonSerialize(): mixed
    {
        $games = $this->getGames()->toArray();
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'games' => $games
        ];
    }

    public function getPassword(): ?string
    {
        return $this->password_hash;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        if ($this->username == 'Henk') $roles[] = 'ROLE_ADMIN';
        return $roles;
    }

    public function eraseCredentials()
    {
        // Implement eraseCredentials() method if necessary.
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
