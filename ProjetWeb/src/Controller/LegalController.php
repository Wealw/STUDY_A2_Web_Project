<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{
    /**
     * @Route("/legal/mentions", name="legal.mentions")
     */
    public function mentions()
    {
        return $this->render('legal/mentions.html.twig');
    }

    /**
     * @Route("/legal/cgv", name="legal.cgv")
     */
    public function cgv()
    {
        return $this->render('legal/cgv.html.twig');
    }
}