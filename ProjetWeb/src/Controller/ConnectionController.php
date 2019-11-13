<?php


namespace App\Controller;


use App\Entity\Connect;
use App\Entity\Merch\ConnectSearch;
use App\Form\ConnectSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnectionController extends AbstractController
{

    /**
     * @Route("/profile/index", name="profile.index")
     */
    public function index()
    {
        $connectSearch = new ConnectSearch();
        $form = $this->createForm(ConnectSearchType::class, $connectSearch);
        return $this->render('connection/index.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/profile/connect", name="profile.connect")
     * @param $id
     * @return Response
     */
    public function connect($id)
    {

        return $this->render('connection/show.html.twig');
    }

    /**
     * @Route("/profile/show", name="profile.show")
     */
    public function show()
    {

    }

    /**
     * @Route("/profile/new", name="profile.new")
     */
    public function new()
    {

    }

}