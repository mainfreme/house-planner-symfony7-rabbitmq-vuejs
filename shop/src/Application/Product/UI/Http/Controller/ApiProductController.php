<?php

namespace App\Application\Product\UI\Http\Controller;

use App\Application\Product\Form\ProductTypeAddForm;
use App\Application\Product\Service\ProductTypeService;
use App\Domain\Product\Entity\ProductType;
use App\Infrastructure\Persistence\Doctrine\Product\ProductTypeRepository;
use App\Validator\ProductTypeValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/product')]
class ApiProductController extends AbstractController
{
    public function __construct(
        private readonly ProductTypeService $productTypeService,
        private ProductTypeValidator $productTypeValidator,
    ) {
    }


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

    #[Route('/type/add', name: 'product_type_add', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $productType = new ProductType();
        $productType->setName($data['name'] ?? '');
        $productType->setLink($data['link'] ?? '');
        $productType->setIsPublic($data['is_public'] ?? false);

        $errors = $this->productTypeValidator->validate($productType);

        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->productTypeService->add($productType);


        return new JsonResponse(['message' => 'Product type created successfully.'], Response::HTTP_CREATED);
    }
}
