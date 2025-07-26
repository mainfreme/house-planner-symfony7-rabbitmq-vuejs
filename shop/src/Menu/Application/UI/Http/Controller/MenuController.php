<?php

declare(strict_types=1);

namespace App\Menu\Application\UI\Http\Controller;

use App\Menu\Application\Service\MenuService;
use App\Product\Infrastructure\Persistence\Doctrine\ProductTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/menu', name: 'menu')]
class MenuController extends AbstractController
{

    public function __construct(private readonly ProductTypeRepository $typeProductRepository)
    {
    }

    #[Route('/', name: 'menu_show')]
    public function menu(): Response
    {
        $menuService = new MenuService($this->typeProductRepository);
        $menu = $menuService->getMenuItems();


        return $this->render('@menu_admin/menu/menu.html.twig', [
            'menuItems' => $menu,
        ]);
    }


}
