<?php


namespace App\Controller\Admin;


use App\Entity\Social\EventType;
use App\Form\EventCategoriesType;
use App\Repository\EventTypeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEventTypesController extends AbstractController
{

    /**
     * @var EventTypeRepository
     */
    private $eventTypeRepository;
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * AdminEventTypesController constructor.
     * @param EventTypeRepository $eventTypeRepository
     * @param ObjectManager $em
     */
    public function __construct(EventTypeRepository $eventTypeRepository, ObjectManager $em)
    {
        $this->eventTypeRepository = $eventTypeRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/events/categories", name="admin.categories.index")
     * @return Response
     */
    public function index(): Response
    {
        $categories = $this->eventTypeRepository->findAll();

        return $this->render("admin/events/categories/index.html.twig", [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/categories/create", name="admin.categories.new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $categories = new EventType();
        $form = $this->createForm(EventCategoriesType::class, $categories);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($categories);
            $this->em->flush();
            return $this->redirectToRoute("admin.events.index", [], 302);
        }

        return $this->render("admin/events/categories/new.html.twig", [
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/categories/edit/{id}", name="admin.categories.edit", methods={"GET|POST"})
     * @param EventType $eventType
     * @param Request $request
     * @return Response
     */
    public function edit(EventType $eventType, Request $request): Response
    {
        $form = $this->createForm(EventCategoriesType::class, $eventType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute("admin.events.index");
        }

        return $this->render("/admin/events/categories/edit.html.twig", [
            'event' => $eventType,
            'form' => $form->createView()
        ]);
    }

}