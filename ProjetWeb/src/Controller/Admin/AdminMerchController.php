<?php


namespace App\Controller\Admin;


use App\Entity\Merch\Product;
use App\Entity\Merch\Command;
use App\Entity\Merch\ProductType;
use App\Repository\CommandProductRepository;
use App\Repository\CommandRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminMerchController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var ProductTypeRepository
     */
    private $productTypeRepository;
    /**
     * @var CommandRepository
     */
    private $commandRepository;
    /**
     * @var CommandProductRepository
     */
    private $commandProductRepository;


    public function __construct(ProductRepository $productRepository, ProductTypeRepository $productTypeRepository, CommandRepository $commandRepository, CommandProductRepository $commandProductRepository)
    {
        $this->productRepository = $productRepository;
        $this->productTypeRepository = $productTypeRepository;
        $this->commandRepository = $commandRepository;
        $this->commandProductRepository = $commandProductRepository;
    }

    /*
     * CAREFUL : ALL THE ROUTES ARE A/.. INSTEAD OF ADMIN/... BECAUSE THERE'S NO LOGIN IMPLEMENTED YET
     */

    /**
     * @Route("a/merch", name="admin.merch.index")
     * @return Response
     */
    public function index() : Response
    {
        return $this->render('admin/merch/index.html.twig');
    }

    /**
     * @Route("a/merch/product", name="admin.merch.product")
     * @return Response
     */
    public function productList() : Response
    {
        $products = $this->productRepository->findAll();
        return $this->render('admin/merch/product/index.html.twig',
            [
                'products' => $products
            ]);
    }

    /**
     * @Route("a/merch/product/{id}/edit", name="admin.merch.product.id")
     * @return Response
     */
    public function editProduct() : Response
    {
        $products = $this->productRepository->findAll();
        return $this->render('admin/merch/product/edit.html.twig',
            [
                'products' => $products
            ]);
    }

    /**
     * @Route("a/merch/product/add", name="admin.merch.product.add")
     * @return Response
     */
    public function addProduct() : Response
    {
        return $this->render('admin/merch/product/new.html.twig');
    }

    /**
     * @Route("a/merch/product/{id}/orderable", name="admin.merch.product.orderable")
     * @return Response
     */
    public function Product() : Response
    {
        $products = $this->productRepository->findAll();
        return $this->render('admin/merch/product/index.html.twig',
            [
                'products' => $products
            ]);
    }

    /**
     * @Route("a/merch/type", name="admin.merch.type.index")
     * @return Response
     */
    public function productTypeList() : Response
    {
        $productTypes = $this->productTypeRepository->findAll();
        return $this->render('admin/merch/type/index.html.twig',
            [
                'types' => $productTypes
            ]);
    }

    /**
     * @Route("a/merch/type/add", name="admin.merch.type.add")
     * @return Response
     */
    public function productTypeAdd() : Response
    {
        return $this->render('admin/merch/type/add.html.twig');
    }

    /**
     * @Route("a/merch/type/{id}/delete", name="admin.merch.type.delete")
     * @return Response
     */
    public function productTypeDelete() : Response
    {
        $productTypes = $this->productTypeRepository->findAll();
        return $this->render('admin/merch/type/index.html.twig',
            [
                'types' => $productTypes
            ]);
    }


    /**
     * @Route("a/merch/command", name="admin.merch.command.index")
     * @return Response
     */
    public function commandList() : Response
    {
        $commands = $this->commandRepository->findAll();
        return $this->render('admin/merch/command/index.html.twig',
            [
                'commands' => $commands
            ]);
    }

    /**
     * @Route("a/merch/command/{id}", name="admin.merch.command.show")
     * @param Command $command
     * @return Response
     */
    public function commandReceipt(Command $command) : Response
    {
        $products = $this->productRepository->findAll();
        $commandProducts = $this->commandProductRepository->findBy(array('command' => $command->getId()));
        return $this->render('admin/merch/command/show.html.twig',
            [
                'command' => $command,
                'commandProducts' => $commandProducts,
                'products' => $products
            ]);
    }
}