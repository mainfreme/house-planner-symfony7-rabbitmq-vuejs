<?php

declare(strict_types=1);

namespace App\Client\Application\UI\Http\Controller;

use App\Client\Domain\Enum\TypeClientEnum;
use App\Validator\Constraints\CsvFile;
use App\Validator\Constraints\CsvFileEmpty;
use PHPStan\Type\StringType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\TypeInfo\Type\EnumType;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Route('/client')]
class ClientController extends AbstractController
{
    public function __construct(
        private readonly RequestStack    $requestStack,
    )
    {
    }

    #[Route('/', name: 'client_index')]
    public function index(): Response
    {

        return $this->render('@client/index.html.twig', [
        ]);
    }

    #[Route('/create', name: 'client_create')]
    public function create(): Response
    {
        return $this->render('@client/create_form.html.twig', [
            'variable' => 'Witaj w Symfony!',
        ]);
    }

    #[Route('/edit/{id}', name: 'client_edit')]
    public function edit(int $id): Response
    {

        return $this->render('@client/create_form.html.twig', [
            'variable' => 'Witaj w Symfony!',
        ]);
    }


    private function form()
    {
        return $this->createFormBuilder()
            ->add('client_type', EnumType::class, [
                'class' => TypeClientEnum::class,
                'label' => 'Typ klienta',
                'required' => true,
                'constraints' => [],
            ])
            ->add('company_name', StringType::class, [
                'label' => 'Nazwa firmy',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new CsvFile(),
                    new CsvFileEmpty(),
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Prześlij'])
            ->getForm();
    }
}

