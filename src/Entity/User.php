<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;



#[Entity]
#[ApiResource]
class User implements \JsonSerializable {
    #[Column(unique:true)] #[Id] #[GeneratedValue] private int $id;
    #[Column] private string $name;
    #[Column] private string $email; //unique:true)
    #[Column] private string $password_hash;

    public function __construct(string $name, string $email, string $password_hash)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password_hash = $password_hash;
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email
        );
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function getPasswordHash(): string { return $this->password_hash; }
    public function setPasswordHash(string $password_hash): void { $this->password_hash = $password_hash; }
}