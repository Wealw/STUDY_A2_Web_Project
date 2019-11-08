<?php

namespace App\Controller;

use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PicturesController extends AbstractController
{

    /**
     * @var PictureRepository
     */
    private $repository;


    /**
     * PicturesController constructor.
     * @param PictureRepository $repository
     */
    public function __construct(PictureRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/events/pictures/{id}", name="pictures.show")
     * @param $id
     * @return Response
     */
    public function show($id): Response
    {
        $picture = $this->repository->find($id);
        if ($picture === null) {
            return $this->redirectToRoute('events.index', [], 302);
        }

        return $this->render('pictures/show.html.twig', [
            'picture' => $picture
        ]);
    }

}