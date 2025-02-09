<?php 

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: "utilisateur")]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(name: "id_utilisateur", type: "string", unique: true)] 
    private ?string $id = null;

    #[ORM\Column(name: "email", type: "string", unique: true)]
    private string $email;

    #[ORM\Column(name: "password", type: "string")]
    private string $password;

    #[ORM\Column(name: "role", type: "json")]
    private array $roles = [];

    public function __construct()
    {
        $this->roles[] = 'ROLE_USER';
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    
    public function getUserIdentifier(): string
    {
        return $this->email; 
    }

    public function eraseCredentials() {}
}
