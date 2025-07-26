<?php

declare(strict_types=1);

namespace App\Product\Application\UI\Http\Controller\Api;

use App\Product\Application\Service\ProductTypeService;
use App\Product\Domain\Entity\ProductType;
use App\Validator\ProductTypeValidator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/product-type')]
class ApiProductTypeController extends AbstractController
{
    public function __construct(
        private readonly ProductTypeService   $productTypeService,
        private readonly ProductTypeValidator $productTypeValidator,
    )
    {
    }

    #[Route('/list', name: 'api_product_type_list', methods: 'GET')]
    public function list(): JsonResponse
    {
        try {
            $productCategoryCollectionList = $this->productTypeService->getList();
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['items' => $productCategoryCollectionList->getArray()], Response::HTTP_OK);
    }

    #[Route('/add', name: 'api_product_type_add', methods: 'POST')]
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
