<?php

namespace App\Controller\Admin;

use App\Repository\EventRepository;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @var EventRepository
     */
    private $eventRepository;
    /**
     * @var PictureRepository
     */
    private $pictureRepository;

    /**
     * AdminController constructor.
     * @param EventRepository $eventRepository
     * @param PictureRepository $pictureRepository
     */
    public function __construct(EventRepository $eventRepository, PictureRepository $pictureRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->pictureRepository = $pictureRepository;
    }

    /**
     * @Route("/admin", name="admin.index")
     */
    public function index()
    {
        $events = $this->eventRepository->findNextVisible();
        $pictures = $this->pictureRepository->findLatestPosted();

        return $this->render("admin/index.html.twig", [
            'events' => $events,
            'pictures' => $pictures
        ]);
    }

}