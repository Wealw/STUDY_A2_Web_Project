<?php


namespace App\Controller;


use App\Entity\Merch\Product;
use App\Entity\Merch\ProductSearch;
use App\Form\ProductSearchType;
use App\Repository\ProductRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator) : Response
    {
        $search = new ProductSearch();
        $form = $this->createForm(ProductSearchType::class, $search);
        $form->handleRequest($request);
        $products = $paginator->paginate(
            $this->productRepository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            15
        );
        return $this->render('merch/index.html.twig',
            [
                'products' => $products,
                'form' => $form->createView()
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