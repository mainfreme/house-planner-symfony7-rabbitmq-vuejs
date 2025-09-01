<?php

declare(strict_types=1);

namespace App\Client\Application\Service;

use App\Client\Domain\Entity\Contact;
use App\Client\Domain\Repository\ClientRepositoryInterface;
use App\Client\Domain\Repository\ClientContactRepositoryInterface;
use App\Infrastructure\Helper\CacheHelper;
use App\Shared\Application\Dto\ColumnArray;
use App\Shared\Application\Dto\ColumnCollectionDto;
use App\Shared\Application\Service\TransformService;

class ClientContactService
{
    private const CACHE_KEY = 'client_contact';
    private const TTL = 3600;

    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly CacheHelper               $cacheHelper,
        private readonly TransformService $transformService
    )
    {
    }


}
