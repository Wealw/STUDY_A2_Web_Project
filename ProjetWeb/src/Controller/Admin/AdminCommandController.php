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


class AdminCommandController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /*
     * @var CommandRepository
     */
    private $commandRepository;
    /**
     * @var CommandProductRepository
     */
    private $commandProductRepository;


    public function __construct(ProductRepository $productRepository, CommandRepository $commandRepository, CommandProductRepository $commandProductRepository)
    {
        $this->productRepository = $productRepository;
        $this->commandRepository = $commandRepository;
        $this->commandProductRepository = $commandProductRepository;
    }
    /**
     * @Route("admin/merch/command", name="admin.merch.command.index")
     * @return Response
     */
    public function index() : Response
    {
        $commands = $this->commandRepository->findAll();
        return $this->render('admin/merch/command/index.html.twig',
            [
                'commands' => $commands
            ]);
    }

    /**
     * @Route("admin/merch/command/{id}", name="admin.merch.command.show")
     * @param Command $command
     * @return Response
     */
    public function show(Command $command) : Response
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