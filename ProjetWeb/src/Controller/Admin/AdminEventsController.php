<?php


namespace App\Controller\Admin;


use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminEventsController extends AbstractController
{
    /**
     * @var EventRepository
     */
    private $repository;

    /**
     * AdminEventsController constructor.
     * @param EventRepository $repository
     */
    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/events", name="admin.events.index")
     */
    public function index()
    {
        $events = $this->repository->findAll();

        return $this->render("admin/events/index.html.twig", [
            'events' => $events
        ]);
    }

}