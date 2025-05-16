<?php

namespace App\Application\Settings\Service;


use App\Application\Menu\Command\ScheduleMenuRefreshCommand;
use App\Infrastructure\Persistence\Doctrine\Product\ProductTypeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SettingsService
{

    private ?ProductTypeRepository $productTypeRepository = null;

    public function __construct(
        private ManagerRegistry       $doctrine,
        private UrlGeneratorInterface $urlGenerator,
        private bool                  $cached = true,
    )
    {
    }

    public function getSettingsList(): array
    {


        return unserialize(settingsList);
    }

    private function generateList(): array
    {

    }


}
