<?php

namespace App\Application\Document\UI\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/template')]
class TemplateController extends AbstractController
{

    #[Route('/', name: 'template_index')]
    public function index(): Response
    {


        return $this->render('@template/index.html.twig', [
        ]);
    }

}
