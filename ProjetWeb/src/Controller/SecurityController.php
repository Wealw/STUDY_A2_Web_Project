<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="security.login")
     * @return Response
     */
    public function login(): Response
    {
        return $this->render("security/login.html.twig");
    }


    public function signup()
    {
        // TODO: sign up new user
    }

}