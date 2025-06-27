<?php

namespace App\Application\Menu\Service;

use App\Application\Menu\Command\ScheduleMenuRefreshCommand;
use App\Domain\Product\Entity\ProductType;
use App\Infrastructure\Persistence\Doctrine\Product\ProductTypeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Cache\ItemInterface;

class MenuService
{

    private const CACHE_KEY = 'menu_cache';
    private const TTL = 3600; // 1h
    private const REFRESH_BEFORE_EXPIRY = 300; // Odśwież na 5 min przed TTL

    private ?ProductTypeRepository $productTypeRepository = null;

    public function __construct(
        private ManagerRegistry       $doctrine,
        private \Redis                $cache,
        private UrlGeneratorInterface $urlGenerator,
        private MessageBusInterface   $commandBus,
        private bool                  $cached = true,
    )
    {
    }

    /**
     * @return array[]
     */
    public function getMenuItems(): array
    {
        $generateMenu = $this->generateMenu();

        if (!$this->cached) {
            return $generateMenu;
        }

        try {
            $menu = unserialize($this->cache->get(self::CACHE_KEY));

            if (!$menu) {
                $this->setCache($generateMenu);
                $menu = $generateMenu;
            }

            return $menu;
        } catch (\Psr\Cache\CacheException | \RedisException $e) {
            return $generateMenu;
        }
    }

    public function setCache(array $menu): void
    {
        $this->cache->set(self::CACHE_KEY, serialize($menu), self::TTL);

        $refreshAt = time() + self::TTL - self::REFRESH_BEFORE_EXPIRY;
        $command = new ScheduleMenuRefreshCommand($refreshAt);
        $this->commandBus->dispatch($command);
    }

    /**
     * @return void
     */
    public function refreshCache(): void
    {
        try {
            $this->cache->unlink(self::CACHE_KEY);
        } catch (\Psr\Cache\CacheException | \RedisException $e) {

        }
        $this->cached = true;
        $this->getMenuItems();
    }

    /**
     * @return array[]
     */
    public function generateMenu(): array
    {
        return [
            [
                'title' => 'Dashboard',
                'icon' => 'https://img.icons8.com/ios-filled/50/dashboard.png',
                'link' => '/dashboard',
                'children' => []
            ],
            [
                'title' => 'Produkty',
                'icon' => 'https://img.icons8.com/ios-filled/50/user.png',
                'link' => '#',
                'children' => $this->getProductTypeSubmenu()
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
                    ['title' => 'Akcje', 'link' => $this->urlGenerator->generate('settings_action', ['name' => ''], UrlGeneratorInterface::RELATIVE_PATH)],
                ]
            ]
        ];
    }

    /**
     * @return array[]
     */
    private function getProductTypeSubmenu(): array
    {
        $repository = $this->getProductTypeRepository();
        $productRepository = $repository->findActive();
        $typeProductArray = [];
        foreach ($productRepository as $type) {
            /** TypeProdukt */
            $typeProductArray[] = [
                'title' => $type->getName().' '.$type->getId(),
                'link' => $this->urlGenerator->generate('product_search', ['name' => $type->getLink()], UrlGeneratorInterface::RELATIVE_PATH),
            ];

        }

        if (empty($typeProductArray)) {
            return [[
                'title' => 'Brak zdefiniowanych typów produktu. Kliknij by dodać',
                'link' => $this->urlGenerator->generate('product_type_list', [], UrlGeneratorInterface::RELATIVE_PATH)
            ]];
        }

        return $typeProductArray;
    }

    /**
     * @return ProductTypeRepository
     */
    private function getProductTypeRepository(): ProductTypeRepository
    {
        if ($this->productTypeRepository === null) {
            $this->productTypeRepository = $this->doctrine->getRepository(ProductType::class);
        }
        return $this->productTypeRepository;
    }

}
