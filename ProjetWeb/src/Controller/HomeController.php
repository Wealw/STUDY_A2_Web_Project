<?php


namespace App\Controller;


use App\Form\SignUpType;
use App\Repository\CommandProductRepository;
use App\Repository\CommandRepository;
use App\Repository\EventRepository;
use App\Repository\ProductRepository;
use App\Security\User;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{


    /**
     * @var EventRepository
     */
    private $eventRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var CommandRepository
     */
    private $commandProductRepository;

    public function __construct(EventRepository $eventRepository, ProductRepository $productRepository, CommandProductRepository $commandProductRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->productRepository = $productRepository;
        $this->commandProductRepository = $commandProductRepository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $nextEvents = $this->eventRepository->findNextVisible();
        $commandProducts = $this->commandProductRepository->findMostSold();
        $products = $this->productRepository->findAll();

        dump($this->getUser());

        return $this->render('home/index.html.twig', [
            'next_events' => $nextEvents,
            'commandProducts' => $commandProducts,
            'products' => $products
        ]);
    }

    /**
     * @Route("/signup", name="security.signup")
     * @param Request $request
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function signUp(Request $request): Response
    {
        $user = new User(null, null, null, null, null, null, null, null, null, null, null, null, 1, 1);
        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUserImagePath("assets/images/user.png");

            $userJson = [
                "user_first_name" => $user->getUserFirstName(),
                "user_last_name" => $user->getUserFirstName(),
                "user_mail" => $user->getUserMail(),
                "user_phone" => (int) $user->getUserPhone(),
                "user_postal_code" => $user->getUserPostalCode(),
                "user_address" => $user->getUserAddress(),
                "user_city" => $user->getUserCity(),
                "user_password" => $user->getUserPassword(),
                "user_image_path" => $user->getUserImagePath(),
                "center_id" => $user->getCenterId(),
                "role_id" => 1
            ];

            $clientPost = new Client();
            $response = $clientPost->request('POST', 'http://127.0.0.1:3000/api/users', ['json' => $userJson]);
        }

        $client = new Client();
        $json = $client->request('GET', 'http://127.0.0.1:3000/api/centers');
        $centers = json_decode($json->getBody(), true, 512, JSON_THROW_ON_ERROR);

        return $this->render("security/signup.html.twig", [
            'form' => $form->createView(),
            'centers' => $centers
        ]);
    }
}