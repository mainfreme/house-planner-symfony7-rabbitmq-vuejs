<?php

namespace App\Application\Product\UI\Http\Controller;

use App\Application\Menu\Service\MenuService;
use App\Application\Product\Form\ProductTypeAddForm;
use App\Domain\Product\Entity\ProductType;
use App\Infrastructure\Persistence\Doctrine\Product\ProductTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product-type')]
class ProductTypeController extends AbstractController
{

    public function __construct(
        private readonly MenuService $menuService
    )
    {
    }

    #[Route('/add', name :'product_type_add', methods: ['get', 'post'])]
    public function productTypeAdd(Request $request, ProductTypeRepository $productRepository): Response
    {
        $productType = new ProductType();
        $form = $this->createForm(ProductTypeAddForm::class, $productType);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($productType);

            $this->menuService->rebuildCache();
            return $this->redirectToRoute('product_type_list');
        }

        return $this->render('@product/productType/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
