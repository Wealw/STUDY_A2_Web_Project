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

    public function csv()
    {

    }

    /**
     * @Route("/admin/images/download", name="admin.images.download")
     */
    public function downloadImages()
    {
        $zip = new ZipArchive();
        $zipName = time() . ".zip";



        /*$zipFile = 'assets/files';
        $zip = new Filesystem(new ZipArchiveAdapter($zipFile));
        $i = 0;
        $attachments = scandir('assets/images/');
        foreach ($attachments as $k => $attachment) {
            if ($attachment > 1) {
                $zip->write(
                    $attachment,
                    file_get_contents($attachment)
                );
            }
        }*/

        $zip->getAdapter()->getArchive()->close();
        $response = new BinaryFileResponse($zipFile);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $response->getFile()->getFilename());
        $response->deleteFileAfterSend(true);
        return $response;
    }

}