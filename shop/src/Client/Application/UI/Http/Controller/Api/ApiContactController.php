<?php

declare(strict_types=1);

namespace App\Client\Application\UI\Http\Controller\Api;

use App\Client\Application\Dto\ClientContactDto;
use App\Client\Application\Dto\ClientContactFilterDto;
use App\Client\Application\Service\ClientContactService;
use App\Shared\Application\Dto\SortDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/contact', name: 'api_contact')]
class ApiContactController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface  $serializer,
        private readonly ClientContactService $clientContactService,
        private readonly ValidatorInterface $validator
    )
    {
    }

    #[Route('/{clientId}/list', name: 'list-contact', methods: ['GET'])]
    public function list(Request $request, int $clientId): JsonResponse
    {
        $filterDto = $this->serializer->denormalize(
            $request->query->all(),
            ClientContactFilterDto::class
        );
        $filterDto->setClientId($clientId);

        $sortDto = $this->serializer->denormalize(
            $request->query->all(),
            SortDto::class
        );

        $errors = $this->validator->validate($filterDto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        $paginatedResultDtoClient = $this->clientContactService->findByCriteria($filterDto, $sortDto);

        return new JsonResponse($paginatedResultDtoClient->toApiArray(), Response::HTTP_OK);
    }

    #[Route('/{clientId}/{contactId}/get', name: 'get-contact', methods: ['GET'])]
    public function getClient(Request $request, int $clientId, int $contactId): JsonResponse
    {

    }

    #[Route('/{clientId}/add', name: 'add-contact', methods: ['POST'])]
    public function add(int $clientId, Request $request): JsonResponse
    {
        $contactDto = $this->serializer->denormalize(
            $request->toArray(),
            ClientContactDto::class
        );

//        $errors = $this->validator->validate($contactDto);
//        if (count($errors) > 0) {
//            $errorMessages = [];
//            foreach ($errors as $error) {
//                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
//            }
//            return new JsonResponse(['errors' => $errorMessages], 400);
//        }

        $this->clientContactService->addClientContact($clientId, $contactDto);


        return new JsonResponse($contactDto->toApiArray(), Response::HTTP_OK);
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
