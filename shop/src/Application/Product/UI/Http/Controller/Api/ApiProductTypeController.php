<?php

namespace App\Application\Product\UI\Http\Controller\Api;

use App\Application\Product\Service\ProductTypeService;
use App\Domain\Product\Entity\ProductType;
use App\Infrastructure\Persistence\Doctrine\Product\ProductTypeRepository;
use App\Validator\ProductTypeValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/product-type')]
class ApiProductTypeController extends AbstractController
{
    public function __construct(
        private readonly ProductTypeService   $productTypeService,
        private readonly ProductTypeValidator $productTypeValidator,
        private readonly ProductTypeRepository $productTypeRepository,
    ) {
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
