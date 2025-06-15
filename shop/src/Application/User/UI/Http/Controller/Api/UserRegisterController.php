<?php

namespace App\Controller\Auth;

use App\Application\User\Dto\UserRegisterDto;
use App\Application\User\Form\UserRegisterForm;
use App\Application\User\Service\UserRegistrationService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/auth')]
class UserRegisterController extends AbstractController
{
    #[Route('/register', name: 'api_register_user', methods: ['POST'])]
    public function register(
        Request                 $request,
        FormFactoryInterface    $formFactory,
        UserRegistrationService $registrationService
    ): JsonResponse
    {
        $dto = new UserRegisterDto();
        $form = $formFactory->create(UserRegisterForm::class, $dto);
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $propertyPath = $error->getOrigin()->getName();
                $errors[$propertyPath][] = $error->getMessage();
            }
            return $this->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $registrationService->register($dto);

        return $this->json(['message' => 'User registered successfully.'], Response::HTTP_CREATED);
    }

    #[Route('/login', name: 'api_login_user', methods: ['POST'])]
    public function login(
        Request                 $request,
        FormFactoryInterface    $formFactory,
        UserRegistrationService $registrationService
    ): JsonResponse
    {
        $dto = new UserRegisterDto();
        $form = $formFactory->create(UserRegisterForm::class, $dto);
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $propertyPath = $error->getOrigin()->getName();
                $errors[$propertyPath][] = $error->getMessage();
            }
            return $this->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $registrationService->register($dto);

        return $this->json(['message' => 'User registered successfully.'], Response::HTTP_CREATED);
    }
}
