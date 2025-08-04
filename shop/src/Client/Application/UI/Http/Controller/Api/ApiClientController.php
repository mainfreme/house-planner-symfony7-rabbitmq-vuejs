<?php

namespace App\Client\Application\UI\Http\Controller\Api;

use App\Client\Application\Dto\ClientFilterDto;
use App\Client\Application\Dto\ColumnDto;
use App\Client\Application\Service\TransformService;
use App\Client\Domain\Entity\Client;
use App\Client\Domain\Repository\ClientRepositoryInterface;
use League\Csv\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/client', name: 'api_client')]
class ApiClientController extends AbstractController
{

    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly SerializerInterface       $serializer,
    )
    {
    }

    #[Route('/get-{id}', name: 'get-client', methods: ['GET'])]
    public function getClient(int $id): JsonResponse
    {

        $clients = $this->clientRepository->findById($id);

        return new JsonResponse($clients, Response::HTTP_OK);
    }

    #[Route('/list', name: 'list-client', methods: ['GET'])]
    public function list(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $filterDto = $this->serializer->denormalize(
            $request->query->all(),
            ClientFilterDto::class
        );

        $errors = $validator->validate($filterDto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        $clients = $this->clientRepository->findByCriteria($filterDto->getArray());

        return new JsonResponse($clients->getArray(), Response::HTTP_OK);
    }

    #[Route('/list-columns', name: 'columns-list-client', methods: ['GET'])]
    public function listColumns(Request $request, TransformService $transformService): JsonResponse
    {
        try {
            $transColumns = $transformService->getColumnsName(Client::class)
                ->transformColumns()
                ->getColumnsTransformDto(ColumnDto::class);
        } catch (Exception $e) {
            dd($e->getMessage());
        }

        return new JsonResponse($transColumns, Response::HTTP_OK);
    }



//    #[Route('/{id}', name: 'get-client', methods: ['GET'])]
//    public function getClient(): JsonResponse
//    {
//
//    }
//
//    #[Route('/add', name: 'add-client', methods: ['POST'])]
//    public function add(): JsonResponse
//    {
//
//    }
//
//
    #[Route('/update/{id}', name: 'update-client', methods: ['PUT', 'PATCH'])]
    public function edit(): JsonResponse
    {
        return new JsonResponse([''], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete-client', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $client = $this->clientRepository->findById($id);
        if (NULL === $client) {
            return new JsonResponse(['message' => 'Nie odnaleziono klienta'], Response::HTTP_NOT_FOUND);
        }

        $removeBool = $this->clientRepository->remove($client);

        if ($removeBool) {
            return new JsonResponse(['message' => 'Poprawnie usunięto'], Response::HTTP_OK);
        }

        return new JsonResponse(['message' => 'Nie udało się usunięto klienta'], Response::HTTP_NOT_FOUND);
    }
//
//
//
//    #[Route('/{id}/list', name: 'list-client-address', methods: ['GET'])]
//    public function listAddress(Request $request, ValidatorInterface $validator): JsonResponse
//    {
//    }
//
    #[Route('/{id}/addresses', name: 'get-client-address', methods: ['GET'])]
    public function getClientAddress(): JsonResponse
    {

        return new JsonResponse([''], Response::HTTP_OK);
    }
//
//    #[Route('/add', name: 'add-client-address', methods: ['POST'])]
//    public function addAddress(): JsonResponse
//    {
//
//    }
//
//
//    #[Route('/edit/{id}', name: 'edit-client-address', methods: ['POST'])]
//    public function editAddress(): JsonResponse
//    {
//
//    }
//
//
//    #[Route('/{id}', name: 'delete-client-address', methods: ['DELETE'])]
//    public function deleteAddress(): JsonResponse
//    {
//
//    }

    #[Route('/{id}/contacts', name: 'get-contacts-list', methods: ['GET'])]
    public function contactsList(): JsonResponse
    {


        return new JsonResponse([''], Response::HTTP_OK);
    }
}
