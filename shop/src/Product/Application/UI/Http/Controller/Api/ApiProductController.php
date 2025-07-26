<?php

declare(strict_types=1);

namespace App\Product\Application\UI\Http\Controller\Api;

use App\Product\Application\Dto\PriceRangeDto;
use App\Product\Application\Dto\ProductFilterDto;
use App\Product\Domain\Repository\ProductRepositoryInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/product')]
class ApiProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepositoryInterface   $productRepository,
        private readonly SerializerInterface $serializer,
    )
    {
    }

    #[Route('/list', name: 'api_product_list', methods: 'GET')]
    public function getList(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $filterDto = $this->serializer->denormalize(
            $request->query->all(),
            ProductFilterDto::class
        );

        $errors = $validator->validate($filterDto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        $products = $this->productRepository->findByCriteria($filterDto->toArray());

        return new JsonResponse($products->getArray(),Response::HTTP_OK);
    }

    #[Route('/house-configurator/save', name: 'house-config-save', methods: 'POST')]
    public function saveHouseConfig(Request $request): JsonResponse
    {

        dd($request->query->all());
    }

    #[Route('/range-price/{category}', name: 'product_range_price', defaults: ['category' => ''], methods: ['GET'])]
    public function getRangePrice(string $category = ''): JsonResponse
    {
        $range = $this->productRepository->findMinMaxPrice($category);

        $piceRangeDto = $this->serializer->denormalize($range, PriceRangeDto::class);

        return new JsonResponse($piceRangeDto->toArray(), Response::HTTP_OK);
    }

}
