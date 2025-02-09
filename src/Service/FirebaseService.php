<?php 

namespace App\Service;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FirebaseService
{
    private Auth $auth;

    public function __construct(ParameterBagInterface $params)
    {
        // Get the project directory using Symfony's Kernel
        $projectDir = $params->get('kernel.project_dir'); 

        // Define the path to Firebase credentials
        $credentialsPath = $projectDir . '/config/firebase_credentials.json';
        
        // Log the credentials path for debugging
        error_log('Firebase credentials path: ' . $credentialsPath);

        // Check if credentials file exists
        if (!file_exists($credentialsPath)) {
            throw new \RuntimeException('Firebase credentials est introuvable. verifier: ' . $credentialsPath);
        }

        // Initialize Firebase Auth
        $factory = (new Factory())->withServiceAccount($credentialsPath);
        $this->auth = $factory->createAuth();
    }

    /*public function verifyIdToken(string $idToken)
    {
        return $this->auth->verifyIdToken($idToken);
    }*/

    public function verifyIdToken(string $idToken)
    {
        // Verify the ID token and return the decoded token
        $verifiedIdToken = $this->auth->verifyIdToken($idToken);

        // Return the entire claims
        return $verifiedIdToken->claims()->all();
    }


    /*public function verifyIdToken(string $idToken)
    {
        // Verify the ID token and return the decoded token
        $verifiedIdToken = $this->auth->verifyIdToken($idToken);
    
        // Log the decoded token for debugging purposes
        error_log('Decoded Token: ' . json_encode($verifiedIdToken));
    
        // Return the claims from the DataSet using the get() method
        return $verifiedIdToken->claims()->get('sub');  // Example: Access 'sub' (user ID) claim
    }*/
    
    

}







/*class FirebaseService
{
    private Auth $auth;

    public function __construct()
    {
        $credentialsPath = $_ENV['FIREBASE_CREDENTIALS'] ?? getenv('FIREBASE_CREDENTIALS');

        if (!$credentialsPath || !file_exists($credentialsPath)) {
            throw new \RuntimeException('Firebase credentials est introuvable.');
        }

        $factory = (new Factory)->withServiceAccount(__DIR__ . '/config/firebase_credentials.json');
        $this->auth = $factory->createAuth();
    }

    public function verifyIdToken(string $idToken)
    {
        return $this->auth->verifyIdToken($idToken);
    }
}*/
