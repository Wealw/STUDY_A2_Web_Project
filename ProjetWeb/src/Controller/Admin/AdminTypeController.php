<?php


namespace App\Controller\Admin;


use App\Entity\Merch\Product;
use App\Entity\Merch\Command;
use App\Entity\Merch\ProductType;
use App\Form\ProductTypeType;
use App\Repository\CommandProductRepository;
use App\Repository\CommandRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class AdminTypeController extends AbstractController
{
    /**
     * @var ProductTypeRepository
     */
    private $productTypeRepository;

    public function __construct(ProductTypeRepository $productTypeRepository)
    {
        $this->productTypeRepository = $productTypeRepository;
    }

    /**
     * @Route("admin/productType", name="admin.merch.type.index")
     * @return Response
     */
    public function index() : Response
    {
        $productTypes = $this->productTypeRepository->findAll();
        return $this->render('admin/merch/type/index.html.twig',
            [
                'types' => $productTypes
            ]);
    }

    /**
     * @Route("admin/productType/add", name="admin.merch.type.add")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request) : Response
    {
        $productType = new ProductType();
        $form = $this->createForm(ProductTypeType::class, $productType);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productType);
            $em->flush();
            return $this->redirectToRoute('admin.merch.type.index');
        }

        return $this->render('admin/merch/type/new.html.twig',
            [
                'form' => $form->createView(),
                'type' => $productType
            ]);
    }

    /**
     * @Route("admin/productType/{id}/edit", name="admin.merch.type.edit")
     * @param Request $request
     * @param ProductType $productType
     * @return Response
     */
    public function edit(Request $request, ProductType $productType) : Response
    {
        $form = $this->createForm(ProductTypeType::class, $productType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('admin.merch.type.index');
        }

        return $this->render('admin/merch/type/edit.html.twig',
            [
                'type' => $productType,
                'form' => $form->createView()
            ]);
    }
}