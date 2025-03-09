<?php

namespace App\Application\Menu\UI\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/menu', name: 'menu')]
class MenuController extends AbstractController
{
    #[Route('/', name: 'menu_show')]
    public function menu(): Response
    {
        $menuItems = [
            [
                'title' => 'Dashboard',
                'icon' => 'https://img.icons8.com/ios-filled/50/dashboard.png',
                'link' => '/dashboard',
                'children' => []
            ],
            [
                'isGranted' => ['ROLE_USER'],
                'title' => 'Użytkownicy',
                'icon' => 'https://img.icons8.com/ios-filled/50/user.png',
                'link' => '#',
                'children' => [
                    ['title' => 'Lista użytkowników', 'link' => '/users'],
                    ['title' => 'Dodaj użytkownika', 'link' => '/users/new'],
                ]
            ],
            [
                'isGranted' => ['ROLE_ADMIN'],
                'title' => 'Ustawienia',
                'icon' => 'https://img.icons8.com/ios-filled/50/settings.png',
                'link' => '#',
                'children' => [
                    ['title' => 'Profil', 'link' => '/settings/profile'],
                    ['title' => 'Bezpieczeństwo', 'link' => '/settings/security'],
                ]
            ]
        ];

        return $this->render('@menu_admin/menu/menu.html.twig', [
            'menuItems' => $menuItems
        ]);
    }
}
