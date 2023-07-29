<?php


    namespace App\Controller\api\v1\auth;
    
    //use Symfony\Component\Security\Core\Annotation\Security;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    
    use App\Repository\UserRepository;
    use App\Service\EmailService;
    use App\Utils\Globals;
    use App\Utils\ManifestHttp;
    use App\Utils\Security;
    use App\Utils\SECURITY_UTILS;
    use App\Utils\UTILS;
    use Symfony\Component\Mailer\MailerInterface;


    //Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")

    /**
     * Class SecurityController
     * @package App\Controller\api\v1\auth
     * @Route("/api/v1/auth", name="api_ctrl_login_")
     * 
     */
    class SecurityController extends AbstractController
    {
        private Globals $globals;
        private Security $security;
        private UserRepository $userRepository;
        private EmailService $emailService;
        

        public function __construct(
            Globals $globals, 
            UserRepository $userRepository, 
            EmailService $emailService,
            Security $security
        )
        {
            $this->globals = $globals;
            $this->userRepository = $userRepository;
            $this->emailService = $emailService;
            $this->security = $security;
        }

        /**
         * @Route("/login", name="login", methods={"POST"})
         */
        public function login() 
        {
            $user = $this->getUser();
            //$this->sendMail->sendEmail($mailer, $data->email, "Voici votre code de valideation");
            
            die($user->getUserIdentifier());
            return $this->globals->success([
                "email" => $user->getUserIdentifier(),
                "roles" => $user->getRoles(),
            ]);
        }


        /**
         * @Route("/register", name="register", methods={"POST"})
         * @return Response
         * @param 
         */
        public function register() : Response
        {
            $data = $this->globals->jsondecode();
            if(!isset(
                $data->nom, 
                $data->prenom, 
                $data->email, 
                $data->domicile, 
                $data->telephone,
                $data->roleUser,
                $data->password, 
                $data->nineUser, 
                $data->imageUser, 
                $data->imagePasportUser, 
            )) return $this->globals->error(ManifestHttp::FROM_ERROR);

            if ($this->security->loadPhoneOrEmail($data->email)) {
                $user = $this->userRepository->findOneBy(["email" => $data->email]);
                if ($user) 
                    return $this->globals->error(ManifestHttp::EMAIL_USED);
    
                if (SECURITY_UTILS::checkPassord($data->password)) 
                    return $this->globals->error(ManifestHttp::PASSWORD_TOO_SHORT);
        
                $res = $this->userRepository->registerUser($data);
                if (strcmp($res, UTILS::ADD) == 0) {
                    $userAdd_or_error = $this->emailService->sendEmailValidationRegister($data);
                    if (strcmp($userAdd_or_error, UTILS::ADD) == 0) {
                        //$qrcode = $this->emailService->generateQRCode($data->email);
                        //return $res;
                        return $this->globals->success([
                            "email" => "sended",
                        ]);
                    }
                }

            }
            var_dump(1);
            return $this->globals->error(ManifestHttp::FROM_ERROR);
        }

        /**
         * @Route("/valideCompte", name="valideCompte", methods={"POST"})
         * @return Response
         * @param 
         */
        public function valideCompte() : Response
        {
            $data = $this->globals->jsondecode();
            if(!isset(
                $data->email, 
                $data->codeValidation
            )) 
            {
                var_dump("on");
                return $this->globals->error(ManifestHttp::FROM_ERROR);
            }

            if ($this->security->loadPhoneOrEmail($data->email)) {
                $user = $this->userRepository->findOneBy(["email" => $data->email]);
                if (!$user) 
                    return $this->globals->error(ManifestHttp::USER_NOT_FOUND);
        
                $userAdd_or_error = $this->emailService->valideCompte($data);
                if (strcmp($userAdd_or_error, UTILS::USER_SERTIFIED) == 0) 
                    return $this->globals->success(["User :" => UTILS::USER_SERTIFIED]);

                return $this->globals->success([ManifestHttp::ERROR_MANIFEST]);
            }
            return $this->globals->error(ManifestHttp::FROM_ERROR);
        }

        /**
         * @Route("/resendCode", name="resendCode", methods={"POST"})
         * @return Response
         * @param 
         */
        public function resendCode() : Response
        {
            $data = $this->globals->jsondecode();
            if(!isset(
                $data->email
            )) return $this->globals->error(ManifestHttp::FROM_ERROR);
            

            if ($this->security->loadPhoneOrEmail($data->email)) {
                $user = $this->userRepository->findOneBy(["email" => $data->email]);
                if (!$user) 
                    return $this->globals->error(ManifestHttp::USER_NOT_FOUND);
        
                $userAdd_or_error = $this->emailService->resendCode($data);
                if (strcmp($userAdd_or_error, UTILS::USER_SERTIFIED) == 0) 
                    return $this->globals->success(["email" => "sended"]);
                    
                return $this->globals->success(["Code-error" => $userAdd_or_error]);
            }
            return $this->globals->error(ManifestHttp::FROM_ERROR);
        }

        /**
         * @Route("/listeUser", name="listeUser", methods={"GET"})
         * @return JsonResponse
         */
        public function listeUser(): JsonResponse
        {
            // Récupérer tous les utilisateur
            $users = $this->userRepository->findAll();

            // Retourner la réponse JSON avec la liste des utilisateurs
            return $this->json($users);
        }

        /**
         * @Route("/logout", name="app_logout")
         */
        public function logout(): void
        {
            // Méthode vide, la déconnexion est gérée par le système de sécurité
        }
        
    }
    

?>