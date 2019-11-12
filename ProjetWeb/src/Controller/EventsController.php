<?php

namespace App\Controller;

use App\Entity\Social\Event;
use App\Entity\Social\EventSearch;
use App\Entity\Social\Impression;
use App\Form\EventSearchType;
use App\Repository\EventRepository;
use App\Repository\EventTypeRepository;
use App\Repository\ImpressionRepository;
use App\Repository\PictureRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @var ObjectManager
     */
    private $em;

    /**
     * EventsController constructor.
     * @param EventRepository $repository
     * @param PictureRepository $pictureRepository
     * @param EventTypeRepository $eventTypeRepository
     * @param ObjectManager $em
     */
    public function __construct(EventRepository $repository, PictureRepository $pictureRepository, EventTypeRepository $eventTypeRepository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->pictureRepository = $pictureRepository;
        $this->eventTypeRepository = $eventTypeRepository;
        $this->em = $em;
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

    /**
     * @Route("/events/{id}/like", name="events.like")
     * @param Event $event
     * @param ObjectManager $manager
     * @param ImpressionRepository $impressionRepository
     * @return RedirectResponse|Response
     */
    public function like(Event $event, ObjectManager $manager, ImpressionRepository $impressionRepository)
    {
        /*if ($event) {
            return $this->redirectToRoute("events.index", [], 302);
        }*/
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("events.show", ['id' => $event->getId()], 302);
        }
        $userDislike = $impressionRepository->findDislike(1)[0];
        $userLike = $impressionRepository->findLike(1)[0];

        $eventsLiked = $userLike->getEvents()->getValues();
        $eventsDisliked = $userDislike->getEvents()->getValues();
        $eventId = $event->getId();
        dump($eventsLiked);

        foreach ($eventsLiked as $eventLiked) {
            if ($eventId === $eventLiked->getId()) {
                $eventLiked->getId();
                $action = 'like';
                $rightEvent = $eventLiked;
                break;
            }
        }

        foreach ($eventsDisliked as $k => $eventDisliked) {
            if ($eventId === $eventDisliked->getId()) {
                $eventDisliked->getId();
                $action = 'dislike';
                $rightEvent = $eventDisliked;
                break;
            }
        }

        if ($action) {

            if ($action === 'like') {
                dump('lololol');
                $userLike->removeEvent($event);
            }

        }

        return $this->render("test.html.twig");
    }

}