<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FirebaseAuthService
{
    private HttpClientInterface $httpClient;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private string $firebaseApiKey;

    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, string $firebaseApiKey)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->firebaseApiKey = $firebaseApiKey;
    }

    public function registerUser(string $email, string $password, array $roles = ['ROLE_USER']): ?Utilisateur
    {
        try {
            $response = $this->httpClient->request('POST', "https://identitytoolkit.googleapis.com/v1/accounts:signUp?key={$this->firebaseApiKey}", [
                'json' => [
                    'email' => $email,
                    'password' => $password,
                    'returnSecureToken' => true
                ]
            ]);

            $data = $response->toArray();

            if (!isset($data['localId'])) {
                throw new \Exception("Échec de l'inscription avec Firebase");
            }

            error_log("Firebase User Created: " . json_encode($data));

            $user = new Utilisateur();
            $user->setId($data['localId']); 
            $user->setEmail($email);
            $user->setPassword(password_hash($password, PASSWORD_BCRYPT)); 
            $user->setRoles($roles);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $user;
        } catch (\Exception $e) {
            error_log("Firebase Registration Error: " . $e->getMessage());

            if ($e instanceof \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface) {
                $errorResponse = $e->getResponse()->toArray(false);
                if (isset($errorResponse['error']['message'])) {
                    return $this->translateFirebaseError($errorResponse['error']['message']);
                }
            }

            throw new \Exception("Erreur lors de l'inscription: " . $e->getMessage());
        }
    }

    
    private function translateFirebaseError(string $firebaseError): void
    {
        $errorMessages = [
            "WEAK_PASSWORD : Password should be at least 6 characters" => "Le mot de passe doit contenir au moins 6 caractères.",
            "EMAIL_EXISTS" => "Cet email est déjà utilisé.",
            "INVALID_EMAIL" => "L'email fourni n'est pas valide.",
        ];
    
        throw new \Exception($errorMessages[$firebaseError] ?? "Une erreur inconnue est survenue.");
    }

        public function loginUser(string $email, string $password)
        {

            $response = $this->httpClient->request('POST', "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key={$this->firebaseApiKey}", [
                'json' => [
                    'email' => $email,
                    'password' => $password,
                    'returnSecureToken' => true
                ]
            ]);
    
            $data = $response->toArray();
    
            if (!isset($data['idToken'])) {
                throw new \Exception("Identification invalide.");
            }

    
            $user = $this->loadUserFromFirebase($data['idToken'],$data['refreshToken'],$data['expiresIn']);
    
            return $user;
        }
    
        private function loadUserFromFirebase(string $idToken,string $refreshToken,string $expiresIn)
        {

            $response = $this->httpClient->request('POST', "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key={$this->firebaseApiKey}", [
                'json' => [
                    'idToken' => $idToken,
                    'refreshToken' => $refreshToken,
                    'expiresIn' => $expiresIn
                ]
            ]);
    
            $data = $response->toArray();
    
            if (!isset($data['users'][0])) {
                throw new \Exception("Utilisateur introuvable.");
            }
    
            $user = new Utilisateur();
            $user->setEmail($data['users'][0]['email']);
    
            return [
                'user' => $user,
                'idToken' => $idToken,
                'refreshToken' => $refreshToken,
                'expiresIn' => $expiresIn
            ];
        }
    



}