<?php

namespace App\Application\Dashboard\UI\Http\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

class DashboardController extends AbstractController
{
    public function __construct(
//        private readonly RequestStack                      $requestStack,
//        private readonly LoggerInterface                   $logger,
    ) {
    }

    #[Route('/', name: 'dashboard_index')]
    public function index(): Response
    {
        return $this->render('@dashboard/index.html.twig', [
            'variable' => 'Witaj w Symfony!',
        ]);
    }
}
