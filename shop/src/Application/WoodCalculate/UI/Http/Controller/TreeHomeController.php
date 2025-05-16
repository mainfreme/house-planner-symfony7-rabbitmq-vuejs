<?php

namespace App\Application\WoodCalculate\UI\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/file/upload')]
class TreeHomeController extends AbstractController
{

    const TYPE_HOME = 'CHILD';


    #[Route('/', name: 'tree_home_index')]
    public function index(Request $request, ?string $uuid): Response
    {
        return $this->render('@tree_home/index.html.twig', [
            'variable' => 'Lista gotowych domków dla dzieci!',
        ]);
    }
}
