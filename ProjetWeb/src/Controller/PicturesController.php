<?php

namespace App\Controller;

use App\Entity\Social\Comment;
use App\Entity\Social\Picture;
use App\Form\CommentType;
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