<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{

    /**
     * @var EventRepository
     */
    private $repository;

    /**
     * @var PictureRepository
     */
    private $pictureRepository;

    /**
     * EventsController constructor.
     * @param EventRepository $repository
     * @param PictureRepository $pictureRepository
     */
    public function __construct(EventRepository $repository, PictureRepository $pictureRepository)
    {
        $this->repository = $repository;
        $this->pictureRepository = $pictureRepository;
    }

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
     * @param $id
     * @return Response
     */
    public function show($id): Response
    {
        $event = $this->repository->find($id);
        $pictures = $this->pictureRepository->findBy(['event' => $id]);

        if ($event === null) {
            return $this->redirectToRoute('events.index', [], 302);
        }

        return $this->render("events/show.html.twig", [
            'event' => $event,
            'pictures' => $pictures
        ]);
    }

}