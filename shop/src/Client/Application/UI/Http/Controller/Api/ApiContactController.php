<?php

declare(strict_types=1);

namespace App\Client\Application\UI\Http\Controller\Api;

use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Client\Domain\Repository\ContactRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/contact', name: 'api_contact')]
class ApiContactController extends AbstractController
{
    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly ContactRepositoryInterface $contactRepository,
        private readonly SerializerInterface $serializer,
    )
    {
    }

    #[Route('/list', name: 'list-contact', methods: ['GET'])]
    public function list(Request $request, ValidatorInterface $validator): JsonResponse
    {
    }

    #[Route('/{id}', name: 'get-contact', methods: ['GET'])]
    public function getClient(): JsonResponse
    {

    }

    #[Route('/add', name: 'add-contact', methods: ['POST'])]
    public function add(): JsonResponse
    {

    }


    #[Route('/edit/{id}', name: 'edit-contact', methods: ['POST'])]
    public function edit(): JsonResponse
    {

    }


    #[Route('/{id}', name: 'delete-contact', methods: ['DELETE'])]
    public function delete(): JsonResponse
    {

    }

}
