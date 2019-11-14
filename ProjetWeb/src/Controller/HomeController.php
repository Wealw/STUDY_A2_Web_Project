<?php


namespace App\Controller;


use App\Repository\CommandProductRepository;
use App\Repository\CommandRepository;
use App\Repository\EventRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{


    /**
     * @var EventRepository
     */
    private $eventRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var CommandRepository
     */
    private $commandProductRepository;

    public function __construct(EventRepository $eventRepository, ProductRepository $productRepository, CommandProductRepository $commandProductRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->productRepository = $productRepository;
        $this->commandProductRepository = $commandProductRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $nextEvents = $this->eventRepository->findNextVisible();
        $commandProducts = $this->commandProductRepository->findMostSold();
        $products = $this->productRepository->findAll();


        dump($commandProducts);
        dump($nextEvents);
        return $this->render('home/index.html.twig', [
            'next_events' => $nextEvents,
            'commandProducts' => $commandProducts,
            'products' => $products
        ]);
    }

}