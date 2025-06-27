<?php

namespace App\Application\Product\UI\Http\Controller;

use App\Application\Menu\Service\MenuService;
use App\Application\Product\Form\ProductSearchType;
use App\Application\Product\Form\ProductTypeAddForm;
use App\Domain\Product\Entity\ProductType;
use App\Infrastructure\Persistence\Doctrine\Product\ProductRepository;
use App\Infrastructure\Persistence\Doctrine\Product\ProductTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/product')]
class ProductController extends AbstractController
{

    public function __construct(private readonly MenuService $menuService)
    {
    }

    #[Route('/list', name: 'product_list')]
    public function index(): Response
    {


        return $this->render('@product/product/list.html.twig', [

        ]);
    }

    #[Route('/{name}', name: 'product_search', defaults: ['name' => null])]
    public function search(Request $request, ProductRepository $productRepository, ?string $name): Response
    {
        $form = $this->createForm(ProductSearchType::class);
        $form->handleRequest($request);

        $criteria = $form->getData();
        if (empty($criteria)) {
            $criteria = ['is_active' => 'true','type'=>($name?'':$name)];
        }
//        $products = $productRepository->findByCriteria($criteria);

        return $this->render('@product/product/list.html.twig', [
            'form' => $form->createView(),
            '$criteria' => $criteria,
//            'products' => $products,
        ]);
    }

//    #[Route('/type/add', name: 'product_type_add')]
//    public function productTypeAdd(Request $request, ProductTypeRepository $productRepository): Response
//    {
//        $productType = new ProductType();
//        $form = $this->createForm(ProductTypeAddForm::class, $productType);
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $productRepository->save($productType);
//
//            $this->menuService->rebuildCache();
//            return $this->redirectToRoute('product_type_list');
//        }
//
//        return $this->render('@product/productType/add.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }

    #[Route('/type/list', name: 'product_type_list')]
    public function list(ProductTypeRepository $productTypeRepository): Response
    {
        $products = $productTypeRepository->findAll();

        return $this->render('@product/productType/table.html.twig', [
            'products' => $products,
        ]);
    }
}

