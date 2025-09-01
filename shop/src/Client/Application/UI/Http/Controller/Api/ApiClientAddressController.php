<?php

declare(strict_types=1);

namespace App\Client\Application\UI\Http\Controller\Api;

use App\Client\Application\Dto\ClientAddressDto;
use App\Client\Application\Dto\ClientAddressFilterDto;
use App\Client\Application\Service\ClientAddressService;
use App\Shared\Application\Dto\SortDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/client/address', name: 'api_client_address')]
class ApiClientAddressController extends AbstractController
{

    public function __construct(
        private readonly SerializerInterface              $serializer,
        private readonly ClientAddressService             $clientAddressService,
        private readonly ValidatorInterface               $validator,
    )
    {
    }

    #[Route('/{id}', name: 'get-client-address', methods: ['GET'])]
    public function getClientAddress(int $id, Request $request): JsonResponse
    {
        /** @var ClientAddressFilterDto $filterDto */
        $filterDto = $this->serializer->denormalize(
            $request->query->all(),
            ClientAddressFilterDto::class
        );

        $filterDto->setClientId($id);

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
            return new JsonResponse($errorMessages, Response::HTTP_NOT_ACCEPTABLE);
        }

        $data = $this->clientAddressService->getClientAddress($filterDto, $sortDto);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/{clientId}/add', name: 'add-client-address', methods: ['POST'])]
    public function addAddress(int $clientId, Request $request): JsonResponse
    {
        $clientAddressDto = $this->serializer->denormalize(
            $request->toArray(),
            ClientAddressDto::class
        );

        try {
            $data = $this->clientAddressService->addClientAddress($clientId, $clientAddressDto);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }


    #[Route('/{clientId}/update', name: 'update-client-address', methods: ['PUT'])]
    public function updateAddress(int $clientId, Request $request): JsonResponse
    {
        $clientAddressDto = $this->serializer->denormalize(
            $request->toArray(),
            ClientAddressDto::class
        );

        try {
            $data = $this->clientAddressService->updateClientAddress($clientId, $clientAddressDto);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/{id}/primary', name: 'change-primary-client-address', methods: ['PUT'])]
    public function changePrimaryAddress(int $id, Request $request): JsonResponse
    {
        $clientAddressDto = $this->serializer->denormalize(
            $request->toArray(),
            ClientAddressDto::class
        );

        try {
            $this->clientAddressService->changePrimaryAddress($id, $clientAddressDto);
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_FORBIDDEN);
        }

        return new JsonResponse([], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete-client-address', methods: ['DELETE'])]
    public function deleteAddress(int $id): JsonResponse
    {
        $removeBool = $this->clientAddressService->removeClientAddress($id);
        if ($removeBool) {
            return new JsonResponse(['message' => 'Poprawnie usunięto'], Response::HTTP_OK);
        }

        return new JsonResponse(['message' => 'Nie udało się usunięto adres klienta'], Response::HTTP_NOT_FOUND);
    }
}
