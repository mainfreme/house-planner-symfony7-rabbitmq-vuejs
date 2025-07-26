<?php

namespace App\Client\Application\UI\Http\Controller\Api;

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

    #[Route('/get/{id}', name: 'get-user', methods: ['GET'])]
    public function getClient()
    {

    }

    #[Route('/user', name: 'add', methods: ['POST'])]
    public function add()
    {

    }


    #[Route('/user/{id}', name: 'edit', methods: ['POST'])]
    public function edit()
    {

    }


    #[Route('/user/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete()
    {

    }
}
