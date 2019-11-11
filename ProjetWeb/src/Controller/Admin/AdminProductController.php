<?php


namespace App\Controller\Admin;


use App\Entity\Merch\Product;
use App\Entity\Merch\Command;
use App\Entity\Merch\ProductType;
use App\Form\ProductType as ProductForm;
use App\Repository\CommandProductRepository;
use App\Repository\CommandRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminProductController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private $productRepository;


    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /*
     * CAREFUL : ALL THE ROUTES ARE A/.. INSTEAD OF ADMIN/... BECAUSE THERE'S NO LOGIN IMPLEMENTED YET
     */

    /**
     * @Route("admin/merch/product", name="admin.merch.product.index")
     * @return Response
     */
    public function index() : Response
    {
        $products = $this->productRepository->findAll();
        return $this->render('admin/merch/product/index.html.twig',
            [
                'products' => $products
            ]);
    }

    /**
     * @Route("admin/merch/product/{id}/edit", name="admin.merch.product.id")
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function edit(Request $request, Product $product) : Response
    {
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin.merch.product.index');
        }
        return $this->render('admin/merch/product/edit.html.twig',
            [
                'product' => $product,
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("admin/merch/product/add", name="admin.merch.product.add")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request) : Response
    {
        $product = new Product();
        $form = $this->createForm(ProductForm::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->persist($product);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin.merch.product.index');
        }

        return $this->render('admin/merch/product/new.html.twig',
            [
                'product' => $product,
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/admin/merch/product/{id}/orderable", name="admin.merch.product.orderable.invert")
     * @param Product $product
     * @return Response
     */
    public function invert(Product $product) : Response
    {
        if($product->getIsOrderable() === true)
        {
            $product->setIsOrderable(false);

        } else{
            $product->setIsOrderable(true);
        }
        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('admin.merch.product.index');
    }
}