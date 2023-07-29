<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Utils\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /*private UserRepository $userRepository;
    private Globals $globals;
    private $data;

    public function __construct(
        UserRepository $userRepository,
        Globals $globals, 
        string $data
    )
    {
        $this->userRepository = $userRepository;
        $this->globals = $globals;
        $this->data = $data;
    }*/


    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        /*$users = $this->userRepository->findByUserName(
            $request->query->get('email')
        );
        die($users);
        if (!is_null($users->getEmail())) {
            //die($user->getUserIdentifier());
            return $this->render('home/home.html.twig', [
                'controller_name' => 'HomeController',
            ]);
        } else {
            return $this->render('login/login.html.twig', [
                'controller_name' => 'LoginController',
            ]);
        }*/
           
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);

    }

        /**
         * @Route("/login", name="login", methods={"POST"})
         */
        #[Route('/userC', name: 'userC', )]
        public function getUserConnected()
        {
            //$this->data = $this->globals->jsondecode();
        }
}
