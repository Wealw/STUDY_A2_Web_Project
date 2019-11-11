<?php


namespace App\Controller;


use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{


    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $nextEvents = $this->eventRepository->findNextVisible();

        dump($nextEvents);

        return $this->render('home/index.html.twig', [
            'next_events' => $nextEvents
        ]);
    }

}