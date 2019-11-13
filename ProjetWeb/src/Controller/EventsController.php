<?php

namespace App\Controller;

use App\Entity\Social\{Event, EventSearch, Participation};
use App\Entity\Social\Impression;
use App\Form\EventSearchType;
use App\Repository\{EventRepository, EventTypeRepository, ImpressionRepository, PictureRepository};
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{RedirectResponse, Request, Response, JsonResponse};
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
     * @param Event $event
     * @return Response
     */
    public function show(Event $event): Response
    {
        if ($event === null) {
            return $this->redirectToRoute('events.index', [], 302);
        }
        $pictures = $event->getPictures()->getValues();
        $type = $event->getEventType();
        $impressions = $event->getImpression();

        $countLike = 0;
        $countDislike = 0;
        $action = null;
        foreach ($impressions as $impression) {
            if ($impression->getImpressionType() === 'like') {
                $countLike++;
            } else {
                $countDislike++;
            }
            if ($impression->getImpressionUserId() === 1) {
                $action = $impression->getImpressionType();
            }
        }

        return $this->render("events/show.html.twig", [
            'event' => $event,
            'pictures' => $pictures,
            'type' => $type,
            'count_like' => $countLike,
            'count_dislike' => $countDislike,
            'action' => $action
        ]);
    }

    /**
     * @Route("/events/{id}/like", name="events.like")
     * @param Event $event
     * @param ObjectManager $em
     * @param ImpressionRepository $impressionRepository
     * @return RedirectResponse|Response
     */
    public function like(Event $event, ObjectManager $em, ImpressionRepository $impressionRepository)
    {
        if (!$event) {
            return $this->redirectToRoute("events.index", [], 302);
        }
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("events.show", ['id' => $event->getId()], 302);
        }
        $eventId = $event->getId();

        $impressionLike = $impressionRepository->findLike(1)[0];
        $impressionDislike = $impressionRepository->findDislike(1)[0];

        $eventsLiked = $impressionLike->getEvents()->getValues();
        $eventsDisliked = $impressionDislike->getEvents()->getValues();

        $impressionLike->removeEvent($event);
        $impressionDislike->removeEvent($event);
        $em->persist($event);

        foreach ($eventsLiked as $eventLiked) {
            if ($eventId === $eventLiked->getId()) {
                $em->flush();
                return $this->json(['action' => 1], 200);
                break;
            }
        }

        foreach ($eventsDisliked as $eventDisliked) {
            if ($eventId === $eventDisliked->getId()) {
                $impressionLike->addEvent($event);
                $em->persist($event);
                $em->flush();
                return $this->json(['action' => 2], 200);
                break;
            }
        }

        $impressionLike->addEvent($event);
        $em->persist($event);
        $em->flush();
        return $this->json(['action' => 0], 200);

    }

    /**
     * @Route("/events/{id}/dislike", name="events.dislike")
     * @param Event $event
     * @param ObjectManager $em
     * @param ImpressionRepository $impressionRepository
     * @return JsonResponse|RedirectResponse
     */
    public function dislike(Event $event, ObjectManager $em, ImpressionRepository $impressionRepository)
    {
        if (!$event) {
            return $this->redirectToRoute("events.index", [], 302);
        }
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("events.show", ['id' => $event->getId()], 302);
        }
        $eventId = $event->getId();

        $impressionLike = $impressionRepository->findLike(1)[0];
        $impressionDislike = $impressionRepository->findDislike(1)[0];

        $eventsLiked = $impressionLike->getEvents()->getValues();
        $eventsDisliked = $impressionDislike->getEvents()->getValues();

        $impressionLike->removeEvent($event);
        $impressionDislike->removeEvent($event);
        $em->persist($event);

        foreach ($eventsDisliked as $eventDisliked) {
            if ($eventId === $eventDisliked->getId()) {
                $em->flush();
                return $this->json(['action' => 1], 200);
                break;
            }
        }

        foreach ($eventsLiked as $eventLiked) {
            if ($eventId === $eventLiked->getId()) {
                $impressionDislike->addEvent($event);
                $em->persist($event);
                $em->flush();
                return $this->json(['action' => 2], 200);
                break;
            }
        }

        $impressionDislike->addEvent($event);
        $em->persist($event);
        $em->flush();
        return $this->json(['action' => 0], 200);
    }

    /**
     * @Route("/events/{id}/participate", name="events.participate")
     * @param Event $event
     * @return Response
     */
    public function participate(Event $event): Response
    {
        if ($event === null) {
            return $this->redirectToRoute('events.index', [], 302);
        }
        $participation = new Participation();
        $participation
            ->setEvent($event)
            ->setParticipationUserId(1);
        $this->em->persist($participation);
        $this->em->flush();

        $impressions = $event->getImpression();

        return $this->redirectToRoute("events.show", [
            'id' => $event->getId()
        ], 302);
    }

}