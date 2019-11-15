<?php


namespace App\Controller;

use App\Entity\Merch\Command;
use App\Entity\Merch\CommandProduct;
use App\Notification\CommandNotification;
use App\Repository\ProductRepository;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException as GuzzleExceptionAlias;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    /**
     * @Route("/cart", name="cart.index")
     * @param SessionInterface $session
     * @param ProductRepository $productRepository
     * @param Request $request
     * @return Response
     */
    public function index(SessionInterface $session, ProductRepository $productRepository, Request $request)
    {

        $this->getCart($session, $request);
        $cart = $session->get('cart', []);
        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'product' => $productRepository->findOneBy(['id' => $id]),
                'quantity' => $quantity
            ];
        }

        $total = 0;

        foreach ($cartWithData as $product)
        {
            $totalProduct = $product['product']->getProductPrice() * $product['quantity'];
            $total += $totalProduct;
        }

        return $this->render('cart/index.html.twig',
            [
                'items' => $cartWithData,
                'total' => $total
            ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart.add")
     * @param $id
     * @param SessionInterface $session
     * @return RedirectResponse
     */
    public function add($id, SessionInterface $session, Request $request)
    {
        $cart = $session->get('cart', []);

        if(!empty($cart[$id]))
        {
            $cart[$id]++;
        } else
        {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);

        $this->persist($session, $request);

        return $this->redirectToRoute('cart.index');
    }

    /**
     * @Route("/cart/delete/{id}", name="cart.delete")
     * @param $id
     * @param SessionInterface $session
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete($id, SessionInterface $session, Request $request)
    {
        $cart = $session->get('cart', []);

        if(!empty($cart[$id]))
        {
            unset($cart[$id]);
        }
        $session->set('cart', $cart);

        $this->persist($session, $request);

        return $this->redirectToRoute("cart.index");
    }

    /**
     * @Route("/cart/delete", name="cart.delete.all")
     * @param SessionInterface $session
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAll(SessionInterface $session, Request $request)
    {
        $cart = $session->get('cart', []);

        foreach ($cart as $id => $quantity)
        {
            unset($cart[$id]);
        }
        $session->set('cart', $cart);

        $this->persist($session, $request);

        return $this->redirectToRoute("cart.index");
    }

    /**
     * @param SessionInterface $session
     * @param Request $request
     * @return RedirectResponse
     */
    public function persist(SessionInterface $session, Request $request)
    {
        if ($this->getUser() !== null) {
            $cart = $session->get('cart', []);
            if (($request->cookies->get('cart')) !== null) {
                if ($cart == null) {
                    $cookie = new Cookie(
                        'cart',
                        '',
                        time() + (2 * 365 * 24 * 60 * 60)
                    );
                    $res = new Response();
                    $res->headers->setCookie($cookie);
                    $res->send();
                    return $this->redirectToRoute('cart.index');
                }
            }
            $DataCart = "";
            foreach ($cart as $id => $quantity) {
                $DataCart = $DataCart . $id . "-" . $quantity . ";";
            }
            $cookie = new Cookie(
                'cart',
                $DataCart,
                time() + (2 * 365 * 24 * 60 * 60)
            );
            $res = new Response();
            $res->headers->setCookie($cookie);
            $res->send();
        }
        return $this->redirectToRoute('cart.index');
    }

    /**
     * @param SessionInterface $session
     * @param Request $request
     * @return RedirectResponse
     */
    public function getCart(SessionInterface $session, Request $request)
    {
        if (!$this->getUser()) {
            $cart = $session->get('cart', []);

            if ($cart !== null) {
                foreach ($cart as $id => $quantity) {
                    unset($cart[$id]);
                }
            }

            if ((($request->cookies->get('cart')) !== null) && ($cart == null)) {
                $savedCart = ($request->cookies->get('cart'));
                $products = explode(';', $savedCart);

                foreach ($products as $id => $product) {
                    if ($product !== "") {
                        $temp = explode('-', $product);
                        for ($i = 0; $i < $temp[1]; $i++) {
                            if (!empty($cart[$temp[0]])) {
                                $cart[$temp[0]]++;
                            } else {
                                $cart[$temp[0]] = 1;
                            }
                        }
                    }
                }
                $session->set('cart', $cart);
            }
        }
        return $this->redirectToRoute('cart.index');
    }

    /**
     * @Route("/cart/order", name="cart.order")
     * @param ProductRepository $productRepository
     * @param SessionInterface $session
     * @param Swift_Mailer $mailer
     * @return RedirectResponse
     * @throws Exception
     * @throws GuzzleExceptionAlias
     */
    public function order(ProductRepository $productRepository, SessionInterface $session, Swift_Mailer $mailer)
    {
        $cart = $session->get('cart', []);
        foreach ($cart as $id => $quantity)
        {
            $product = $productRepository->findOneBy(['id' => $id]);
            if (($product->getProductInventory()) < $cart[$id] )
            {
                return $this->redirectToRoute("cart.index");
            }
        }
        if($this->getUser())
        {
            $command = new Command();

            $command
                ->setCommandUserId($this->getUser()->getUserId())
                ->setCommandOrderedAt(new DateTime('now'));

            $this->getDoctrine()->getManager()->persist($command);


            foreach ($cart as $id => $quantity)
            {
                $commandProduct = new CommandProduct();

                $product = $productRepository->findOneBy(['id' => $id]);

                $product
                    ->setProductInventory(($product->getProductInventory()) - $cart[$id]);

                $commandProduct
                    ->setCommand($command)
                    ->setProduct($product)
                    ->setQuantity($cart[$id]);

                $this->getDoctrine()->getManager()->persist($product);
                $this->getDoctrine()->getManager()->persist($commandProduct);

            }
            $this->getDoctrine()->getManager()->flush();

            $client = new Client();

            $firstResponse = $client->request('GET', 'http://127.0.0.1:3000/api/users');
            $dataUsers = json_decode($firstResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);
            foreach ($dataUsers as $key => $dataUser)
            {
                $secondResponse = $client->request('GET', 'http://127.0.0.1:3000/api/roles/' . $dataUser["role_id"]);
                $dataRole = json_decode($secondResponse->getBody(), true, 512, JSON_THROW_ON_ERROR);

                if ($dataRole['role_name'] == "ROLE_BDE")
                {
                    // Create a message
                    $message = (new Swift_Message('New Order Made'))
                        ->setFrom('projetweeb@gmail.com')
                        ->setTo($dataUser['user_mail'])
                        ->setBody('New command made ! Check it in the ADMIN/COMMAND | ID : ' . $command->getId());

                    // Send the message
                    $mailer->send($message);
                }
            }
            return $this->redirectToRoute("cart.delete.all");
        }else{
            return $this->redirectToRoute("security.login");
        }
    }
}