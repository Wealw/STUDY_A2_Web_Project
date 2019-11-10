<?php


namespace App\Controller;


use App\Entity\Social\EventType;
use App\Repository\EventTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventTypeController extends AbstractController
{
    /**
     * @var EventTypeRepository
     */
    private $repository;

    /**
     * EventTypeController constructor.
     * @param EventTypeRepository $repository
     */
    public function __construct(EventTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/events/categories", name="events.categories.index")
     */
    public function index(): Response
    {
        $categories = $this->repository->findAll();

        return $this->render("events/categories/index.html.twig", [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/events/categories/{id}", name="events.categories.show")
     * @param EventType $eventType
     * @return Response
     */
    public function show(EventType $eventType): Response
    {

    }

}