<?php


namespace App\Controller;


use App\Entity\Connect;
use App\Entity\Merch\ConnectSearch;
use App\Form\ConnectSearchType;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException as GuzzleExceptionAlias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;

class ConnectionController extends AbstractController
{

    /**
     * @Route("/profile/index", name="profile.index")
     */
    public function index()
    {
        return $this->render('connection/index.html.twig');
    }

    /**
     * @Route("/profile/connect/{id}", name="profile.connect")
     * @param $id
     * @param Request $request
     * @return Response
     * @throws GuzzleExceptionAlias
     */
    public function connect($id, Request $request)
    {
        $client = new Client();

        $firstResponse = $client->request('GET', 'http://127.0.0.1:3000/api/users/' . $id);
        $dataUser = json_decode($firstResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);


        $secondResponse = $client->request('GET', 'http://127.0.0.1:3000/api/roles/' . $dataUser["role_id"]);
        $dataRole = json_decode($secondResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $thirdResponse = $client->request('GET', 'http://127.0.0.1:3000/api/centers/' . $dataUser["center_id"]);
        $dataSite = json_decode($thirdResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if(isset($user_id))
        {
            //TODO IMPLEMENTS COOKIECHECKER IF ISSET
        }else{
            $user_id = new Cookie(
                'connected_user_id',
                $dataUser["user_id"],
                time() + ( 2 * 365 * 24 * 60 * 60)
            );

        }


        var_dump($user_id->getValue());
        return $this->render('connection/show.html.twig', [
            'dataUser' => $dataUser,
            'dataRole' => $dataRole,
            'dataSite' => $dataSite
        ]);
    }

    /**
     * @Route("/profile/show", name="profile.show")
     */
    public function show()
    {
        return $this->render('connection/show.html.twig');
    }

    /**
     * @Route("/profile/new", name="profile.new")
     */
    public function new()
    {

    }

}