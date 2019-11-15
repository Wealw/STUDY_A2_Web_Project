<?php


namespace App\Controller\Admin;


use App\Entity\Social\Admin\AdminEventSearch;
use App\Entity\Social\Event;
use App\Form\AdminEventSearchType;
use App\Form\EventType;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use Doctrine\Common\Persistence\ObjectManager;
use GuzzleHttp\Client;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $eventSearch = new AdminEventSearch();
        $form = $this->createForm(AdminEventSearchType::class, $eventSearch);
        $form->handleRequest($request);

        $events = $this->repository->findAll();

        return $this->render("admin/events/index.html.twig", [
            'events' => $events,
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
        $event->setEventIsVisible(0);
        $this->em->flush();
        return $this->redirectToRoute("admin.events.index", [], 302);
    }

    /**
     * @Route("/admin/events/search/{search}", name="admin.events.search")
     * @param $search
     * @param CommentRepository $commentRepository
     * @return JsonResponse
     */
    public function search(CommentRepository $commentRepository, $search = null): JsonResponse
    {
        $comments = $commentRepository->findLike($search);
        $events = $this->repository->findLike($search);
        $jsonEvents = [];

        foreach ($events as $k => $event) {
            $jsonEvents[$k]['id'] = $events[$k]->getId();
            $jsonEvents[$k]['eventName'] = $events[$k]->getEventName();
            $jsonEvents[$k]['eventDate'] = $events[$k]->getEventDate()->format('d F Y H:i');
            $jsonEvents[$k]['createdAt'] = $events[$k]->getEventCreatedAt()->format('d F Y');
            $jsonEvents[$k]['createdBy'] = $events[$k]->getEventCreatedBy();
            $jsonEvents[$k]['isVisible'] = $events[$k]->getEventIsVisible();
        }

        return $this->json($jsonEvents, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/admin/events/csvize/{id}", name="admin.events.csvize", methods={"GET"})
     * @param Event $event
     * @param Request $request
     * @return Response
     */
    public function csvize(Event $event, Request $request)
    {
        try {
            $csvName = $event->getId() . '_' . $event->getEventName();
            $event = $this->repository->find($event->getId());
            $participations = $event->getParticipation()->getValues();
            $response = new StreamedResponse(static function () use ($participations) {
                $client = new Client();
                $handle = fopen('php://output', 'r+');
                fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
                foreach ($participations as $participation) {
                    $response = $client->request('GET', 'http://127.0.0.1:3000/api/users/' . $participation->getParticipationUserId());
                    if ($response->getStatusCode() !== 200) {
                        throw new Exception('External error');
                    }
                    $data = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
                    $tab = array($data['user_first_name'], $data['user_last_name']);
                    fputcsv($handle, $tab, ';');
                }
                fclose($handle);
            });
            $response->headers->set('Content-Type', 'application/force-download');
            $response->headers->set('Content-Disposition', 'attachment; filename="' . $csvName . '".csv"');
            return $response;
        } catch (Exception $e) {
            return $this->redirectToRoute('admin.events.index');
        }
    }
}