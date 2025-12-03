<?php

declare(strict_types=1);

namespace App\Client\Application\UI\Http\Controller\Api;

use App\Client\Application\Query\GetCompanyByNipQuery;
use App\Client\Application\Query\Handler\GetCompanyByNipHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/company', name: 'api_company')]
class ApiCompanyController extends AbstractController
{
    public function __construct(
        private readonly GetCompanyByNipHandler $getCompanyByNipHandler,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    ) {
    }

    #[Route('/by-nip', name: '_by_nip', methods: ['POST'])]
    public function getCompanyByNip(Request $request): JsonResponse
    {
        try {
            // Pobierz dane z request body
            $data = json_decode($request->getContent(), true);

            if (!isset($data['nip']) || empty($data['nip'])) {
                return new JsonResponse(
                    ['error' => 'NIP jest wymagany'],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // Utwórz Query
            $query = new GetCompanyByNipQuery((string)$data['nip']);

            // Waliduj Query
            $errors = $this->validator->validate($query);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }

                return new JsonResponse(
                    ['errors' => $errorMessages],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // Wykonaj Query przez Handler
            $response = $this->getCompanyByNipHandler->handle($query);

            // Sprawdź czy znaleziono firmę
            if ($response->name === null) {
                return new JsonResponse(
                    ['message' => 'Nie znaleziono firmy o podanym numerze NIP'],
                    Response::HTTP_NOT_FOUND
                );
            }

            return new JsonResponse(
                $response->toArray(),
                Response::HTTP_OK
            );
        } catch (\RuntimeException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => 'Wystąpił nieoczekiwany błąd: ' . $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}

