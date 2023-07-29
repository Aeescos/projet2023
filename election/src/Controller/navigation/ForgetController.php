<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForgetController extends AbstractController
{
    #[Route('/forget', name: 'forget')]
    public function index(): Response
    {
        return $this->render('forget/index.html.twig', [
            'controller_name' => 'ForgetController',
        ]);
    }
}
