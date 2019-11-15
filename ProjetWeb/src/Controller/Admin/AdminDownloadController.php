<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;
use ZipArchive;


class AdminDownloadController extends AbstractController
{
    /**
     * @Route("/download/images", name="download.images")
     * @return BinaryFileResponse
     */
    public function download()
    {
        $path = explode("\src", __DIR__ );
        $imagesProds = scandir($path[0] . '\public\assets\images\products');
        $imgPath = $path[0] . '\public\assets\images\products';
        $zipPath = $path[0] . '\public\assets\downloadimages.zip';
        $zip = new ZipArchive();
        $zip->open($zipPath, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
        foreach ($imagesProds as $key=>$imagesProd)
        {
            if($key > 1)
            {
                $zip->addFile($zipPath, $imagesProd);
            }
        }
        $zip->close();
        return $this->file($path[0] . '\public\assets\downloadimages.zip');
    }
}