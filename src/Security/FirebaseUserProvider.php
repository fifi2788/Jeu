<?php

namespace App\Security;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FirebaseUserProvider implements UserProviderInterface
{
    private EntityManagerInterface $entityManager;
    private HttpClientInterface $httpClient;
    private string $firebaseApiKey;

    public function __construct(EntityManagerInterface $entityManager, HttpClientInterface $httpClient, string $firebaseApiKey)
    {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
        $this->firebaseApiKey = $firebaseApiKey;
    }

    public function loadUserByIdentifier(string $email): UserInterface
    {
        // Check if user exists in PostgreSQL
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            throw new UserNotFoundException("Utilisateur {$email} introuvable.");
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new \Exception('Class Utilisateur invalide');
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    public function createFirebaseUser(string $email, string $password): User
    {
        $response = $this->httpClient->request('POST', "https://identitytoolkit.googleapis.com/v1/accounts:signUp?key={$this->firebaseApiKey}", [
            'json' => [
                'email' => $email,
                'password' => $password,
                'returnSecureToken' => true
            ]
        ]);

        $data = $response->toArray();

        if (!isset($data['localId'])) {
            throw new \Exception("Inscription Firebase echoue: " . json_encode($data));
        }

        $user = new User();
        $user->setId($data['localId']); 
        $user->setEmail($email);
        $user->setPassword($password); 
        $user->setRoles(["ROLE_USER"]); 

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
