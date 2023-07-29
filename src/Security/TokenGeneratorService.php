<?php


    namespace App\Security;

    use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Component\PropertyAccess\PropertyAccess;

    class TokenGeneratorService 
    {
        private $jwtEncoder;
        private $propertyAccessor;
    
        public function __construct(JWTEncoderInterface $jwtEncoder)
        {
            $this->jwtEncoder = $jwtEncoder;
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }
    
        public function generate(UserInterface $user, \DateTimeInterface $iatTimeIn): string
        {
            $payload = [
                'username' => $user->getUserIdentifier(),
                'roles' => $user->getRoles(),
                'iat' => $iatTimeIn->getTimestamp(),
                'exp' => $iatTimeIn->getTimestamp() , // Expiration time of 1 hour
            ];
    
            return $this->jwtEncoder->encode($payload);
        }
    
        /*public function secureEndpoint(JWTTokenManagerInterface $jwtManager, JWTTokenDecoderInterface $jwtDecoder)
        {
            // Récupérez le jeton JWT de l'en-tête d'autorisation
            $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];
            $jwt = str_replace('Bearer ', '', $authorizationHeader);
    
            // Validez et décryptez le jeton JWT
            $decodedToken = $jwtDecoder->decode($jwt);
    
            // Vous pouvez accéder aux informations du jeton JWT décrypté
            $username = $decodedToken['username'];
            $roles = $decodedToken['roles'];
    
            // Effectuez des opérations supplémentaires en fonction des informations du jeton JWT
    
            // Retournez la réponse appropriée
            return new JsonResponse(['message' => 'Endpoint sécurisé']);
        }*/
    }

?>