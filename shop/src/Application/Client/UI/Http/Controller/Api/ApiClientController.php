<?php

namespace App\Application\Client\UI\Http\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/client', name: 'api_client')]
class ApiClientController extends AbstractController
{

    public function __construct()
    {
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(GetCustomerQuery $query): JsonResponse
    {
        $customers = $query->execute();
        return $this->json($customers);
    }

}
