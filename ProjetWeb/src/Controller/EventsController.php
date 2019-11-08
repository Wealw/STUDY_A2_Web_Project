<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{

    /**
     * @Route("/events", name="events.index")
     * @return Response
     */
    public function index(): Response
    {


        return $this->render("events/index.html.twig");
    }

    /**
     * @Route("/events/{id}", name="events.show")
     * @return Response
     */
    public function show(): Response
    {
        return $this->render("events/show.html.twig");
    }

}