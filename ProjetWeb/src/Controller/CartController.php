<?php


namespace App\Controller;

use App\Repository\ProductRepository;
use Metadata\Tests\Driver\Fixture\C\SubDir\C;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    /**
     * @Route("/cart", name="cart.index")
     * @param SessionInterface $session
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function index(SessionInterface $session, ProductRepository $productRepository)
    {
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
    public function add($id, SessionInterface $session)
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

        return $this->redirectToRoute('cart.index');
    }

    /**
     * @Route("/cart/delete/{id}", name="cart.delete")
     * @param $id
     * @param SessionInterface $session
     * @return RedirectResponse
     */
    public function delete($id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);

        if(!empty($cart[$id]))
        {
            unset($cart[$id]);
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute("cart.index");
    }

    /**
     * @Route("/cart/persist", name="cart.persist")
     * @param SessionInterface $session
     * @param Request $request
     * @return RedirectResponse
     */
    public function persist(SessionInterface $session, Request $request)
    {
        $cart = $session->get('cart', []);
        if(($request->cookies->get('cart')) !== null)
        {
            if($cart == null)
            {
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
            time() + ( 2 * 365 * 24 * 60 * 60)
        );
        $res = new Response();
        $res->headers->setCookie($cookie);
        $res->send();
        return $this->redirectToRoute('cart.index');
    }

    /**
     * @Route("/cart/get", name="cart.getCart")
     * @param SessionInterface $session
     * @param Request $request
     * @return RedirectResponse
     */
    public function getCart(SessionInterface $session, Request $request)
    {
        $cart = $session->get('cart', []);

        if ($cart !== null)
        {
            foreach ($cart as $id => $quantity) {
                unset($cart[$id]);
            }
        }

        if((($request->cookies->get('cart')) !== null) && ($cart == null))
        {
            $savedCart = ($request->cookies->get('cart'));
            $products = explode(';', $savedCart);

            foreach ($products as $id => $product)
            {
                if($product !== "")
                {
                    $temp = explode('-', $product);
                    for($i = 0; $i < $temp[1]; $i++)
                    {
                        if(!empty($cart[$temp[0]]))
                        {
                            $cart[$temp[0]]++;
                        } else
                        {
                            $cart[$temp[0]] = 1;
                        }
                    }
                }
            }
            $session->set('cart', $cart);
        }
        return $this->redirectToRoute('cart.index');
    }
}