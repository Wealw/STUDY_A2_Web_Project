<?php

namespace App\Controller\Admin;

use App\Repository\EventRepository;
use App\Repository\PictureRepository;
use League\Flysystem\Filesystem;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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

    public function csv()
    {

    }

    /**
     * @Route("/admin/images/download", name="admin.images.download")
     */
    public function downloadImages()
    {
        $zip = new \ZipArchive();
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