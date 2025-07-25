<?php

//declare(strict_types=1);

namespace App\Product\Application\UI\Http\Controller;

use App\Menu\Application\Service\MenuService;
use App\Product\Application\Form\ProductSearchForm;
use App\Product\Application\Form\ProductTypeAddForm;
use App\Product\Domain\Entity\ProductType;
use App\Product\Infrastructure\Persistence\Doctrine\ProductRepository;
use App\Product\Infrastructure\Persistence\Doctrine\ProductTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/product')]
class ProductController extends AbstractController
{

//    public function __construct(private readonly MenuService $menuService)
//    {
//    }

    #[Route('/list', name: 'product_list')]
    public function index(): Response
    {


        return $this->render('@product/product/list.html.twig', [

        ]);
    }

    #[Route('/{name}', name: 'product_search', defaults: ['name' => null])]
    public function search(Request $request, ?string $name): Response
    {
        return $this->render('@product/product/list.html.twig', [
            'category' => $name,
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

//    #[Route('/type/list', name: 'product_type_list')]
//    public function list(ProductTypeRepository $productTypeRepository): Response
//    {
//        $products = $productTypeRepository->findAll();
//
//        return $this->render('@product/productType/table.html.twig', [
//            'products' => $products,
//        ]);
//    }
}

