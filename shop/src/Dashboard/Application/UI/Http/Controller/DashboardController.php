<?php

namespace App\Dashboard\Application\UI\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
