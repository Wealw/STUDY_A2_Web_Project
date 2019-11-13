<?php


namespace App\Controller;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/login_check", name="security.auth")
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @return Response
     * @throws GuzzleException
     */
    public function auth(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $client = new Client();
        $response = $client->request('POST', 'http://127.0.0.1:3000/api/login', ['json' => ['user_mail' => $request->get('_username'), 'user_password' => $request->get('_password')]]);
        $datas = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        if ($datas['auth'] === true) {
            $session = new Session();
            $session->start();
            $session->set('name', $datas['token']);
            $response = new RedirectResponse($this->generateUrl('index'));
            $cookie = new Cookie('x-access-token', $datas['token']);
            $response->headers->setCookie($cookie);
            return $response;
        }
        return $this->redirectToRoute('security.login');


    }
}