<?php

namespace App\Controller;

use App\Entity\Social\{Event, EventSearch, Participation};
use App\Entity\Social\Impression;
use App\Form\EventSearchType;
use App\Repository\{EventRepository,
    EventTypeRepository,
    ImpressionRepository,
    ParticipationRepository,
    PictureRepository};
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
     * @param ParticipationRepository $participationRepository
     * @return Response
     * @throws \Exception
     */
    public function index(PaginatorInterface $paginator, Request $request, ParticipationRepository $participationRepository): Response
    {
        $search = new EventSearch();
        $form = $this->createForm(EventSearchType::class, $search);
        $form->handleRequest($request);

        $events = $paginator->paginate(
            $this->repository->findRequestVisible($search),
            $request->query->getInt('page', 1),
            15
        );
        $participations = $participationRepository->findBy(['participation_user_id' => $this->getUser()->getUserId()]);

        return $this->render("events/index.html.twig", [
            'events' => $events,
            'participations' => $participations,
            'form' => $form->createView(),
            'today' => new \DateTime()
        ]);
    }

    /**
     * @Route("/events/{id}", name="events.show")
     * @param Event $event
     * @param ParticipationRepository $participationRepository
     * @return Response
     */
    public function show(Event $event, ParticipationRepository $participationRepository): Response
    {
        if ($event === null) {
            return $this->redirectToRoute('events.index', [], 302);
        }
        $pictures = $event->getPictures()->getValues();
        $type = $event->getEventType();
        $impressions = $event->getImpression();


        $hasParticipated = false;
        if ($this->getUser()) {
            $participation = $participationRepository->findBy([
                'event' => $event,
                'participation_user_id' => $this->getUser()->getUserId()
            ]);

            if ($participation != null) {
                $hasParticipated = true;
            }
        }

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
            'has_participated' => $hasParticipated,
            'today' => new \DateTime(),
            'count_like' => $countLike,
            'count_dislike' => $countDislike,
            'action' => $action
        ]);
    }

    /**
     * @Route("/events/{id}/like", name="events.like")
     * @param Event $event
     * @param ImpressionRepository $impressionRepository
     * @return RedirectResponse|Response
     */
    public function like(Event $event, ImpressionRepository $impressionRepository)
    {
        if (!$event) {
            return $this->redirectToRoute("events.index", [], 302);
        }
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("events.show", ['id' => $event->getId()], 302);
        }
        $eventId = $event->getId();

        [$impressionLike, $impressionDislike] = $this->setImpressions($impressionRepository);

        $eventsLiked = $impressionLike->getEvents()->getValues();
        $eventsDisliked = $impressionDislike->getEvents()->getValues();

        $impressionLike->removeEvent($event);
        $impressionDislike->removeEvent($event);
        $this->em->persist($event);

        foreach ($eventsLiked as $eventLiked) {
            if ($eventId === $eventLiked->getId()) {
                $this->em->flush();
                return $this->json(['action' => 1], 200);
                break;
            }
        }

        foreach ($eventsDisliked as $eventDisliked) {
            if ($eventId === $eventDisliked->getId()) {
                $impressionLike->addEvent($event);
                $this->em->persist($event);
                $this->em->flush();
                return $this->json(['action' => 2], 200);
                break;
            }
        }

        $impressionLike->addEvent($event);
        $this->em->persist($event);
        $this->em->flush();
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

        [$impressionLike, $impressionDislike] = $this->setImpressions($impressionRepository);

        $eventsLiked = $impressionLike->getEvents()->getValues();
        $eventsDisliked = $impressionDislike->getEvents()->getValues();

        $impressionLike->removeEvent($event);
        $impressionDislike->removeEvent($event);
        $this->em->persist($event);

        foreach ($eventsDisliked as $eventDisliked) {
            if ($eventId === $eventDisliked->getId()) {
                $this->em->flush();
                return $this->json(['action' => 1], 200);
                break;
            }
        }

        foreach ($eventsLiked as $eventLiked) {
            if ($eventId === $eventLiked->getId()) {
                $impressionDislike->addEvent($event);
                $this->em->persist($event);
                $this->em->flush();
                return $this->json(['action' => 2], 200);
                break;
            }
        }

        $impressionDislike->addEvent($event);
        $this->em->persist($event);
        $this->em->flush();
        return $this->json(['action' => 0], 200);
    }

    /**
     * @Route("/events/{id}/participate", name="events.participate")
     * @param Event $event
     * @param ParticipationRepository $participationRepository
     * @return Response
     * @throws \Exception
     */
    public function participate(Event $event, ParticipationRepository $participationRepository): Response
    {
        $user = $this->getUser();
        $today = new \DateTime();
        if (!$event || !$user) {
            return $this->redirectToRoute('events.index', [], 302);
        }

        if ($today > $event->getEventDate()) {
            return $this->redirectToRoute('events.show', [
                'id' => $event->getId()
            ]);
        }

        $hasParticipated = $participationRepository->findBy([
            'participation_user_id' => $user->getUserId(),
            'event' => $event
        ]);

        if ($hasParticipated != null) {
            $this->em->remove($hasParticipated[0]);
            $this->em->flush();
            return $this->redirectToRoute("events.show", [
                'id' => $event->getId()
            ], 302);
        }

        $participation = new Participation();
        $participation
            ->setEvent($event)
            ->setParticipationUserId($user->getUserId());
        $this->em->persist($participation);
        $this->em->flush();

        $impressions = $event->getImpression();

        return $this->redirectToRoute("events.show", [
            'id' => $event->getId()
        ], 302);
    }

    /**
     * @param ImpressionRepository $impressionRepository
     * @return array
     */
    private function setImpressions(ImpressionRepository $impressionRepository) {
        $user = $this->getUser();
        $testLike = $impressionRepository->findBy([
            'impression_user_id' => $user->getUserId()
        ]);

        if ($testLike == null) {
            $impressionLike = new Impression();
            $impressionDislike = new Impression();
            $impressionLike
                ->setImpressionUserId($user->getUserId())
                ->setImpressionType('like');
            $this->em->persist($impressionLike);
            $impressionDislike
                ->setImpressionUserId($user->getUserId())
                ->setImpressionType('dislike');
            $this->em->persist($impressionDislike);
            $this->em->flush();
        } else {
            $impressionLike = $impressionRepository->findLike($user->getUserId())[0];
            $impressionDislike = $impressionRepository->findDislike($user->getUserId())[0];
        }

        return [$impressionLike, $impressionDislike];
    }

}