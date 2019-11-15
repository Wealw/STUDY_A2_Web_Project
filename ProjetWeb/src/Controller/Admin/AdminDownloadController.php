<?php


namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;
use ZipArchive;

class AdminDownloadController extends AbstractController
{
    /**
     * @Route("/download/products", name="download.products")
     * @return BinaryFileResponse
     */
    public function downloadProd()
    {
        $path = explode("\src", __DIR__);
        $imagesProds = scandir($path[0] . '\public\assets\images\products');
        $imgPath = $path[0] . '\public\assets\images\products';
        $zipPath = $path[0] . '\public\assets\downloadimages.zip';
        $zip = new ZipArchive();
        $zip->open($zipPath, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
        foreach ($imagesProds as $key => $imagesProd) {
            if ($key > 1) {
                $zip->addFile($zipPath, $imagesProd);
            }
        }
        $zip->close();
        return $this->file($path[0] . '\public\assets\downloadimages.zip');
    }

    /**
     * @Route("/download/events", name="download.events")
     * @return BinaryFileResponse
     */
    public function downloadEv()
    {
        $path = explode("\src", __DIR__);
        $imagesEvs = scandir($path[0] . '\public\assets\images\events');
        $imgPath = $path[0] . '\public\assets\images\events';
        $zipPath = $path[0] . '\public\assets\downloadevents.zip';
        $zip = new ZipArchive();
        $zip->open($zipPath, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
        foreach ($imagesEvs as $key => $imagesEv) {
            if ($key > 1) {
                $zip->addFile($zipPath, $imagesEv);
            }
        }
        $zip->close();
        return $this->file($path[0] . '\public\assets\downloadevents.zip');
    }


    /**
     * @Route("/download/pictures", name="download.pictures")
     * @return BinaryFileResponse
     */
    public function downloadPic()
    {
        $path = explode("\src", __DIR__);
        $imagesPics = scandir($path[0] . '\public\assets\images\pictures');
        $imgPath = $path[0] . '\public\assets\images\pictures';
        $zipPath = $path[0] . '\public\assets\downloadpictures.zip';
        $zip = new ZipArchive();
        $zip->open($zipPath, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
        foreach ($imagesPics as $key => $imagesPic) {
            if ($key > 1) {
                $zip->addFile($zipPath, $imagesPic);
            }
        }
        $zip->close();
        return $this->file($path[0] . '\public\assets\downloadpictures.zip');
    }
}