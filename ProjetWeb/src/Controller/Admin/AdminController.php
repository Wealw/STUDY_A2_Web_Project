<?php

namespace App\Controller\Admin;

use App\Repository\CommandRepository;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use App\Repository\PictureRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductTypeRepository;
use League\Flysystem\Filesystem;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use ZipArchive;

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
     * @param EventRepository $eventRepository
     * @param CommentRepository $commentRepository
     * @param ProductTypeRepository $productTypeRepository
     * @param ProductRepository $productRepository
     * @param CommandRepository $commandRepository
     * @return Response
     */
    public function index(EventRepository $eventRepository, CommentRepository $commentRepository, ProductTypeRepository $productTypeRepository, ProductRepository $productRepository, CommandRepository $commandRepository)
    {
        $eventType = $eventRepository->findAll();
        $events = $this->eventRepository->findAll();
        $pictures = $this->pictureRepository->findAll();
        $comments = $commentRepository->findAll();
        $productType = $productTypeRepository->findAll();
        $products = $productRepository->findAll();
        $commands = $commandRepository->findAll();

        return $this->render("admin/index.html.twig", [
            'event_categories' => $eventType,
            'events' => $events,
            'pictures' => $pictures,
            'comments' => $comments,
            'product_categories' => $productType,
            'products' => $products,
            'commands' => $commands
        ]);
    }

}