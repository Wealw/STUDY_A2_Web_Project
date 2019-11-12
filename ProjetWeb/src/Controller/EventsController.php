<?php

namespace App\Controller;

use App\Entity\Social\EventSearch;
use App\Form\EventSearchType;
use App\Repository\EventRepository;
use App\Repository\EventTypeRepository;
use App\Repository\PictureRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @var EventTypeRepository
     */
    private $eventTypeRepository;

    /**
     * EventsController constructor.
     * @param EventRepository $repository
     * @param PictureRepository $pictureRepository
     * @param EventTypeRepository $eventTypeRepository
     */
    public function __construct(EventRepository $repository, PictureRepository $pictureRepository, EventTypeRepository $eventTypeRepository)
    {
        $this->repository = $repository;
        $this->pictureRepository = $pictureRepository;
        $this->eventTypeRepository = $eventTypeRepository;
    }

    /**
     * @Route("/events", name="events.index")
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new EventSearch();
        $form = $this->createForm(EventSearchType::class, $search);
        $form->handleRequest($request);

        $today = new \DateTime();

        $events = $paginator->paginate(
            $this->repository->findRequestVisible($search),
            $request->query->getInt('page', 1),
            12
        );


        return $this->render("events/index.html.twig", [
            'events' => $events,
            'form' => $form->createView(),
            'today' => $today
        ]);
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

        $type = $this->eventTypeRepository->findBy(['id' => $event->getEventType()->getId()])[0];

        return $this->render("events/show.html.twig", [
            'event' => $event,
            'pictures' => $pictures,
            'type' => $type
        ]);
    }

}