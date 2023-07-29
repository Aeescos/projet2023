<?php


    namespace App\Security;

    use App\Utils\Globals;
    use DateTimeInterface;
    use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Security\Core\User\UserInterface;

    /**
     * Class ClientController
     * @package App\Security
     * @Route("/api/v1/secure/refresh")
     * 
     */
    class RefreshToken extends AbstractController
    {
        private Globals $globals;
        private DateTimeInterface $iatTime;
    
        public function __construct(Globals $globals)
        {
            $this->globals = $globals;
            $this->iatTime = new \DateTimeImmutable();
        }


        /**
         * @Route("/refreshToken", name="refreshToken", methods={"POST"})
         * @param JWTTokenManagerInterface $tokenManager
         * @return JsonResponse
         */
        public function refreshToken(JWTTokenManagerInterface $tokenManager): JsonResponse
        {
            /** @var UserInterface $user */
            $user = $this->getUser();
            var_dump($user);

            if (!$user) {
                return $this->globals->errorNoFound(['message' => 'User not authenticated']);
            }
            //$getToken = $this->tokenGenerate->generate($user, $this->iatTime);
            return $this->globals->success([
                "email" => $user,
                "Refresh-token" => $tokenManager->create($user),
            ]);
        }

    /**
     * @Route("/token", name="token")
     * @return JsonResponse
     */
    public function token():JsonResponse
    {
        return $this->globals->success([
            "message" => "Bienvenue sur notre API Intelligent(e)"
        ]);
    }


    }

?>