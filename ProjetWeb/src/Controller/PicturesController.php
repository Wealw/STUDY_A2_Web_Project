<?php

namespace App\Controller;

use App\Entity\Social\Comment;
use App\Entity\Social\Event;
use App\Entity\Social\Picture;
use App\Form\CommentType;
use App\Form\PictureType;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use App\Repository\PictureRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return Response
     */
    public function new(Event $event, Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("events.show", [
                'id' => $event->getId()
            ], 302);
        }
        $picture = new Picture();
        $form = $this->createForm(PictureType::class, $picture);
        $form->handleRequest($request);

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

        $eventId = $picture->getEvent()->getId();
        $event = $this->eventRepository->findBy(['id' => $eventId])[0];
        $picture->setEvent($event);

        $pictureRelated = $this->repository->findRelated($picture->getId(), $eventId);
        $comments = $this->commentRepository->findCommentsByPictures($picture->getId());

        return $this->render('pictures/show.html.twig', [
            'picture' => $picture,
            'picturesRelated' => $pictureRelated,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }

}