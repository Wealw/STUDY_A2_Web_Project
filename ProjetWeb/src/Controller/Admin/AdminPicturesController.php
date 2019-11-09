<?php


namespace App\Controller\Admin;


use App\Controller\PicturesController;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminPicturesController extends AbstractController
{

    /**
     * @var PictureRepository
     */
    private $repository;

    public function __construct(PictureRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/pictures", name="admin.pictures.index")
     */
    public function index() {
        $pictures = $this->repository->findAll();

        return $this->render("admin/pictures/index.html.twig", [
           'pictures' => $pictures
        ]);
    }

    /**
     *
     */
    public function delete() {

    }

}