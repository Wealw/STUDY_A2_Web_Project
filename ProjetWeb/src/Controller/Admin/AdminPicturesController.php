<?php


namespace App\Controller\Admin;


use App\Controller\PicturesController;
use App\Entity\Social\Picture;
use App\Repository\PictureRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use ZipArchive;

class AdminPicturesController extends AbstractController
{

    /**
     * @var PictureRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * AdminPicturesController constructor.
     * @param PictureRepository $repository
     * @param ObjectManager $em
     */
    public function __construct(PictureRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
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
     * @Route("/admin/pictures/edit/{id}", name="admin.pictures.delete", methods={"DELETE"})
     * @param Picture $picture
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Picture $picture, Request $request): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $request->get('_token'))) {
            $this->em->remove($picture);
            //$pictures->setIsVisible(0);
            $this->em->flush();
        }
        return $this->redirectToRoute("admin.pictures.index", [], 302);
    }


}