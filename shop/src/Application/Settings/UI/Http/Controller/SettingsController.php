<?php

namespace App\Application\Settings\UI\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/settings')]
class SettingsController extends AbstractController
{

    #[Route('/action', name: 'settings_action', methods: 'GET')]
    public function actionList()
    {



        return $this->render('@product/productType/add.html.twig', [
//            'form' => $form->createView(),
        ]);
    }
}
