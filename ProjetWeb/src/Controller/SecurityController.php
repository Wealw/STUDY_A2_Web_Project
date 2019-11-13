<?php


namespace App\Controller;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/login", name="security.login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @throws GuzzleException
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($request->getMethod() === 'POST') {

            dd("ak");
            $client = new Client();
            $response = $client->request('POST', 'http://127.0.0.1:3000/api/login', ['json' => ['user_mail' => $request->get('_username'), 'user_password' => $request->get('_password')]]);
            $datas = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

            if ($datas['auth'] === true) {
                $this->getUser()->setToken($datas['token']);
            }
            //TODO Implement error messages
        };
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}