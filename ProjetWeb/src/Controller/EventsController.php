<?php

namespace App\Controller;

use App\Entity\Social\EventSearch;
use App\Form\EventSearchType;
use App\Repository\EventRepository;
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
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $search = new EventSearch();
        $form = $this->createForm(EventSearchType::class, $search);
        $form->handleRequest($request);

        $events = $paginator->paginate(
            $this->repository->findRequestVisible($search),
            $request->query->getInt('page', 1),
            12
        );


        return $this->render("events/index.html.twig", [
            'events' => $events,
            'form' => $form->createView()
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

        return $this->render("events/show.html.twig", [
            'event' => $event,
            'pictures' => $pictures
        ]);
    }

}