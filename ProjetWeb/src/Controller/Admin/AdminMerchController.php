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
     * @Route("admin/merch", name="admin.merch.index")
     * @return Response
     */
    public function index() : Response
    {
        return $this->render('admin/merch/index.html.twig');
    }
}