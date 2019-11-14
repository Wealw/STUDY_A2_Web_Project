<?php

namespace App\Controller;

use App\Entity\Social\Comment;
use App\Entity\Social\Event;
use App\Entity\Social\Impression;
use App\Entity\Social\Picture;
use App\Form\CommentType;
use App\Form\PictureType;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use App\Repository\ImpressionRepository;
use App\Repository\ParticipationRepository;
use App\Repository\PictureRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PicturesController extends AbstractController
{

    /**
     * @var PictureRepository
     */
    private $repository;

    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var EventRepository
     */
    private $eventRepository;
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * PicturesController constructor.
     * @param PictureRepository $repository
     * @param CommentRepository $commentRepository
     * @param EventRepository $eventRepository
     * @param ObjectManager $em
     */
    public function __construct(PictureRepository $repository, CommentRepository $commentRepository, EventRepository $eventRepository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->commentRepository = $commentRepository;
        $this->eventRepository = $eventRepository;
        $this->em = $em;
    }

    /**
     * @Route("/events/{event}/pictures/new", name="pictures.new")
     * @param Event $event
     * @param Request $request
     * @param ParticipationRepository $participationRepository
     * @return Response
     * @throws \Exception
     */
    public function new(Event $event, Request $request, ParticipationRepository $participationRepository)
    {
        $user = $this->getUser();
        $today = new \DateTime();
        if (!$user || $event->getEventDate() > $today) {
            return $this->redirectToRoute("events.show", [
                'id' => $event->getId()
            ], 302);
        }

        $hasParticipated = $participationRepository->findBy([
            'event' => $event,
            'participation_user_id' => $user->getUserId()
        ]);

        if ($hasParticipated == null) {
            return $this->redirectToRoute("events.show", [
                'id' => $event->getId()
            ], 302);
        }

        $picture = new Picture();
        $form = $this->createForm(PictureType::class, $picture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture
                ->setPictureUserId($user->getUserId())
                ->setEvent($event);

            $this->em->persist($picture);
            $this->em->flush();
            return $this->redirectToRoute("events.show", [
                'id' => $event->getId()
            ], 302);
        }

        return $this->render("pictures/new.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/events/pictures/{id}", name="pictures.show")
     * @param Picture $picture
     * @param Request $request
     * @return Response
     */
    public function show(Picture $picture, Request $request): Response
    {
        if ($picture === null) {
            return $this->redirectToRoute('events.index', [], 302);
        }
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPicture($picture)
                ->setCommentUserId(1);

            $this->em->persist($comment);
            $this->em->flush();
            $this->redirectToRoute("pictures.show", ['id' => $picture->getId()], 302);
        }

        $impressions = $picture->getImpression();
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

        $eventId = $picture->getEvent()->getId();
        $event = $picture->getEvent();

        $pictureRelated = $this->repository->findRelated($picture->getId(), $eventId);
        $comments = $picture->getComments();

        return $this->render('pictures/show.html.twig', [
            'picture' => $picture,
            'picturesRelated' => $pictureRelated,
            'comments' => $comments,
            'form' => $form->createView(),
            'count_like' => $countLike,
            'count_dislike' => $countDislike,
            'action' => $action,
        ]);
    }

    /**
     * @Route("events/picture/{id}/like", name="pictures.like")
     * @param Picture $picture
     * @param ImpressionRepository $impressionRepository
     * @return JsonResponse|RedirectResponse
     */
    public function like(Picture $picture, ImpressionRepository $impressionRepository)
    {
        if (!$picture) {
            return $this->redirectToRoute("events.index", [], 302);
        }
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("pictures.show", ['id' => $picture->getId()], 302);
        }
        $pictureId = $picture->getId();

        [$impressionLike, $impressionDislike] = $this->setImpressions($impressionRepository);

        $picturesLiked = $impressionLike->getPictures()->getValues();
        $picturesDisliked = $impressionDislike->getPictures()->getValues();

        $impressionLike->removePicture($picture);
        $impressionDislike->removePicture($picture);
        $this->em->persist($picture);

        foreach ($picturesLiked as $pictureLiked) {
            if ($pictureId === $pictureLiked->getId()) {
                $this->em->flush();
                return $this->json(['action' => 1], 200);
                break;
            }
        }

        foreach ($picturesDisliked as $pictureDisliked) {
            if ($pictureId === $pictureDisliked->getId()) {
                $impressionLike->addPicture($picture);
                $this->em->persist($picture);
                $this->em->flush();
                return $this->json(['action' => 2], 200);
                break;
            }
        }

        $impressionLike->addPicture($picture);
        $this->em->persist($picture);
        $this->em->flush();
        return $this->json(['action' => 0], 200);
    }

    /**
     * @Route("events/picture/{id}/dislike", name="pictures.dislike")
     * @param Picture $picture
     * @param ImpressionRepository $impressionRepository
     * @return JsonResponse|RedirectResponse
     */
    public function dislike(Picture $picture, ImpressionRepository $impressionRepository)
    {
        if (!$picture) {
            return $this->redirectToRoute("events.index", [], 302);
        }
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("pictures.show", ['id' => $picture->getId()], 302);
        }
        $pictureId = $picture->getId();

        [$impressionLike, $impressionDislike] = $this->setImpressions($impressionRepository);

        $picturesLiked = $impressionLike->getPictures()->getValues();
        $picturesDisliked = $impressionDislike->getPictures()->getValues();

        $impressionLike->removePicture($picture);
        $impressionDislike->removePicture($picture);
        $this->em->persist($picture);

        foreach ($picturesDisliked as $pictureDisliked) {
            if ($pictureId === $pictureDisliked->getId()) {
                $this->em->flush();
                return $this->json(['action' => 1], 200);
                break;
            }
        }

        foreach ($picturesLiked as $pictureLiked) {
            if ($pictureId === $pictureLiked->getId()) {
                $impressionDislike->addPicture($picture);
                $this->em->persist($picture);
                $this->em->flush();
                return $this->json(['action' => 2], 200);
                break;
            }
        }

        $impressionLike->addPicture($picture);
        $this->em->persist($picture);
        $this->em->flush();
        return $this->json(['action' => 0], 200);
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