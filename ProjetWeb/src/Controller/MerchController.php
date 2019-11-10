<?php


namespace App\Controller;


use App\Entity\Merch\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MerchController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/merch", name="merch.index")
     * @return Response
     */
    public function index() : Response
    {
        $products = $this->productRepository->findAll();
        return $this->render('merch/index.html.twig',
            [
                'products' => $products
            ]);
    }

    /**
     * @Route("/merch/{id}", name="merch.show")
     * @param Product $product
     * @return Response
     */
    public function show(Product $product) : Response
    {
        if($product->getIsOrderable() === false)
        {
            return $this->redirectToRoute('merch.index');
        }
        return $this->render('merch/show.html.twig',
            [
                'product' => $product
            ]);
    }
}