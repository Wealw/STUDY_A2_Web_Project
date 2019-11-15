<?php


namespace App\Controller\Admin;


use App\Entity\Merch\Admin\AdminProductSearch;
use App\Entity\Merch\Product;
use App\Form\AdminProductSearchType;
use App\Form\ProductType as ProductForm;
use App\Repository\ProductRepository;
use App\Repository\ProductTypeRepository;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;


class AdminProductController extends AbstractController
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
     * AdminProductController constructor.
     * @param ProductRepository $productRepository
     * @param ProductTypeRepository $productTypeRepository
     */
    public function __construct(ProductRepository $productRepository, ProductTypeRepository $productTypeRepository)
    {
        $this->productRepository = $productRepository;
        $this->productTypeRepository = $productTypeRepository;
    }

    /**
     * @Route("admin/product", name="admin.merch.product.index")
     * @return Response
     */
    public function index() : Response
    {
        $productSearch = new AdminProductSearch();
        $products = $this->productRepository->findAll();
        $form = $this->createForm(AdminProductSearchType::class, $productSearch);
        return $this->render('admin/merch/product/index.html.twig',
            [
                'products' => $products,
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("admin/product/{id}/edit", name="admin.merch.product.id")
     * @param Request $request
     * @param Product $product
     * @param CacheManager $cacheManager
     * @param UploaderHelper $helper
     * @return Response
     */
    public function edit(Request $request, Product $product, CacheManager $cacheManager, UploaderHelper $helper): Response
    {
        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($product->getImageFile() instanceof UploadedFile) {
                $cacheManager->remove($helper->asset($product, 'imageFile'));
            }
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
     * @Route("admin/product/add", name="admin.merch.product.add")
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
     * @Route("/admin/product/{id}/orderable", name="admin.merch.product.orderable.invert")
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


    /**
     * @Route("/admin/product/search/{search}", name="admin.product.search")
     * @param $search
     * @return JsonResponse
     */
    public function search($search): JsonResponse
    {
        $types = $this->productTypeRepository->findAll();
        $products = $this->productRepository->findLike($search);

        $jsonProducts = null;
        foreach ($products as $key => $product)
        {
            $jsonProducts[$key]['Id'] = $products[$key]->getId();
            $jsonProducts[$key]['productName'] = $products[$key]->getProductName();
            $jsonProducts[$key]['productPrice'] = $products[$key]->getProductPrice();
            $jsonProducts[$key]['productInventory'] = $products[$key]->getProductInventory();
            $jsonProducts[$key]['productDescription'] = $products[$key]->getProductDescription();
            $jsonProducts[$key]['productType'] = $products[$key]->getProductType()->getProductTypeName();
        }
        return $this->json($jsonProducts, 200, ['Content-Type' => 'application/json']);
    }
}