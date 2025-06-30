<?php

namespace App\Application\Product\UI\Http\Controller\Api;


use App\Application\Product\Dto\PriceRangeDto;
use App\Application\Product\Dto\ProductFilterDto;
use App\Application\Product\Form\ProductSearchForm;
use App\Infrastructure\Persistence\Doctrine\Product\ProductRepository;
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
        private readonly ProductRepository $productRepository,
        private readonly SerializerInterface $serializer,
    )
    {
    }




    #[Route('/list', name: 'api_product_list', methods: 'GET')]
    public function getList(Request $request): JsonResponse
    {

        $page = $request->query->getInt('page', 1);

        $products = $this->productRepository->findAll();

        return new JsonResponse(
            [
                'page' => $page,
                'items' => $products,
                'total_pages' => 3
            ],
            Response::HTTP_OK
        );
    }

    #[Route('/house-configurator/save', name: 'house-config-save', methods: 'POST')]
    public function saveHouseConfig(Request $request): JsonResponse
    {
        dd($request);
    }

    #[Route('/range-price', name: 'product_range_price', methods: ['GET'])]
    public function getRangePrice(Request $request, ProductRepository $productRepository): JsonResponse
    {

        $categoryName = $request->query->get('category', '');

        $range = $productRepository->findMinMaxPrice($categoryName);

        $piceRangeDto = $this->serializer->denormalize($range, PriceRangeDto::class);

        return new JsonResponse($piceRangeDto->toArray(), Response::HTTP_OK);
    }

}
