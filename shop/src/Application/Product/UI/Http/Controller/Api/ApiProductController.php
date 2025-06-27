<?php

namespace App\Application\Product\UI\Http\Controller\Api;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/product')]
class ApiProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
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
}
