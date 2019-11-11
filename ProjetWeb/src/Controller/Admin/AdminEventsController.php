<?php


namespace App\Controller\Admin;


use App\Entity\Social\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\EventTypeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEventsController extends AbstractController
{
    /**
     * @var EventRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * AdminEventsController constructor.
     * @param EventRepository $repository
     * @param ObjectManager $em
     */
    public function __construct(EventRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/events", name="admin.events.index")
     * @param EventTypeRepository $eventTypeRepository
     * @return Response
     */
    public function index(EventTypeRepository $eventTypeRepository)
    {
        $events = $this->repository->findAll();
        $categories = $eventTypeRepository->findAll();

        return $this->render("admin/events/index.html.twig", [
            'events' => $events,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/events/new", name="admin.events.new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($event);
            $this->em->flush();
            return $this->redirectToRoute("admin.events.index");
        }

        return $this->render("admin/events/new.html.twig", [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/events/edit/{id}", name="admin.events.edit", methods={"GET|POST"})
     * @param Event $event
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Event $event, Request $request)
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute("admin.events.index");
        }

        return $this->render("/admin/events/edit.html.twig", [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/events/edit/{id}", name="admin.events.delete", methods={"DELETE"})
     * @param Event $event
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Event $event, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->get('_token'))) {
            $event->setEventIsVisible(0);
            $this->em->flush();
        }
        return $this->redirectToRoute("admin.events.index", [], 302);
    }

}