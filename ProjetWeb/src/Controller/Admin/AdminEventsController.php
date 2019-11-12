<?php


namespace App\Controller\Admin;


use App\Entity\Social\Admin\AdminEventSearch;
use App\Entity\Social\Event;
use App\Form\AdminEventSearchType;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\EventTypeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(EventTypeRepository $eventTypeRepository, Request $request, PaginatorInterface $paginator)
    {
        $eventSearch = new AdminEventSearch();
        $form = $this->createForm(AdminEventSearchType::class, $eventSearch);
        $form->handleRequest($request);

        $events = $paginator->paginate(
            $this->repository->findAdminRequest($eventSearch),
            $request->query->getInt('page', 1),
            12
        );

        $categories = $eventTypeRepository->findAll();

        return $this->render("admin/events/index.html.twig", [
            'events' => $events,
            'categories' => $categories,
            'form' => $form->createView()
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

    /**
     * @Route("/admin/events/search/{search}", name="admin.events.search")
     * @param $search
     * @return JsonResponse
     */
    /*public function search($search): JsonResponse
    {
        $events = $this->repository->findLike($search);

        return $this->json([$events], 200);
    }*/

}