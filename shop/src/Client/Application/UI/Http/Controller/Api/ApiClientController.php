<?php

declare(strict_types=1);

namespace App\Client\Application\UI\Http\Controller\Api;

use App\Client\Application\Dto\ClientDto;
use App\Client\Application\Dto\ClientFilterDto;
use App\Client\Application\Service\ClientService;
use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Shared\Application\Dto\SortDto;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/api/client', name: 'api_client')]
class ApiClientController extends AbstractController
{

    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly SerializerInterface       $serializer,
        private readonly ClientService             $clientService
    )
    {
    }

    #[Route('/list', name: 'list-client', methods: ['GET'])]
    public function list(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $filterDto = $this->serializer->denormalize(
            $request->query->all(),
            ClientFilterDto::class
        );

        $sortDto = $this->serializer->denormalize(
            $request->query->all(),
            SortDto::class
        );

        $errors = $validator->validate($filterDto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        $paginatedResultDtoClient = $this->clientService->findByCriteria($filterDto, $sortDto);

        return new JsonResponse($paginatedResultDtoClient->toApiArray(), Response::HTTP_OK);
    }

    #[Route('/list-columns', name: 'columns-list-client', methods: ['GET'])]
    public function listColumns(): JsonResponse
    {
        $allowedFields = $this->clientService->getFields();

        return new JsonResponse($allowedFields->toArray(), Response::HTTP_OK);
    }

    #[Route('/get-{id}', name: 'get-client', methods: ['GET'])]
    public function getClient(int $id): JsonResponse
    {
        try {
            $clients = $this->clientService->getClient($id);

            return new JsonResponse($clients->getArray(), Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    #[Route('/add', name: 'add-client', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $clientDto = $this->serializer->denormalize(
            $request->toArray(),
            ClientDto::class
        );

        try {
            $updateObject = $this->clientService->save($clientDto);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        $clientDto = ClientDto::fromEntity($updateObject);

        return new JsonResponse($clientDto->toApiArray(), Response::HTTP_OK);
    }


    #[Route('/update/{id}', name: 'update-client', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $clientDto = $this->serializer->denormalize(
            $request->toArray(),
            ClientDto::class
        );

        $clientDto->id = $id;

        try {
            $updateObject = $this->clientService->update($clientDto);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($updateObject->toApiArray(), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete-client', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $client = $this->clientService->getClient($id);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        $removeBool = $this->clientRepository->remove($client);

        if ($removeBool) {
            return new JsonResponse(['message' => 'Poprawnie usunięto'], Response::HTTP_OK);
        }

        return new JsonResponse(['message' => 'Nie udało się usunięto klienta'], Response::HTTP_NOT_FOUND);
    }


    #[Route('/import', name: 'api_clients_import')]
    public function importClients(GetFakturowniaClientsHandler $handler): JsonResponse
    {
        $query = new GetFakturowniaClientsQuery(page: 1, perPage: 25);

        /** @var \App\Domain\Client\Entity\Client[] $clients */
        $clients = $handler($query);

        // Zwrócenie danych lub przekazanie do kolejnego Handlera/Serwisu, który zapisze je do DB
        return $this->json([
            'message' => 'Liczba zaimportowanych klientów: ' . count($clients),
            'clients' => $clients
        ]);
    }
}
