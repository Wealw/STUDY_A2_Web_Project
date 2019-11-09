<?php


namespace App\Controller\Admin;


use App\Entity\Social\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     */
    public function index()
    {
        $events = $this->repository->findAll();

        return $this->render("admin/events/index.html.twig", [
            'events' => $events
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
            return $this->redirectToRoute("admin.event.index");
        }

        return $this->render("admin/events/new.html.twig", [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }

}