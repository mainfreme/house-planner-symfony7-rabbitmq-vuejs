<?php

namespace App\Application\CsvProcessing\EventListener;

use App\Domain\CsvProcessing\Enum\CsvFileUploadStatusEnum;
use App\Domain\CsvProcessing\Event\CsvRowProcessedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\CacheInterface;

class CsvRowProcessedEventHandler
{

    public function __construct(
        private readonly LoggerInterface $logger,
        private CacheInterface           $cache,
        private readonly RequestStack    $requestStack,
    )
    {
    }

    #[AsEventListener(event: CsvRowProcessedEvent::class)]
    public function onCsvRowProcessed(CsvRowProcessedEvent $event): void
    {
        $cacheItem = $this->cache->getItem('csv_status_' . $event->getUuid());
        $cacheItem->set([
            'progress' => (int)(($event->getRow() / $event->getTotalRow()) * 100),
            'status' => CsvFileUploadStatusEnum::PENDING,
        ]);
        $cacheItem->expiresAfter(3600);
        $this->cache->save($cacheItem);


        $message = "Obsługa wiersza CSV z: {$event->getUuid()}, Linia: {$event->getRow()}";
        $this->logger->info($message);

        $data = $event->getData();

        // Tutaj możemy zwalidować dane i zapisywać dane do bazy
        $session = $this->requestStack->getSession();
        $session->set("file_progress_{$event->getUuid()}", (int)(($event->getRow() / $event->getTotalRow()) * 100));

        $cacheItem = $this->cache->getItem('csv_' . $event->getUuid());
        $cacheItem->set(['csv' => $data]);
        $cacheItem->expiresAfter(3600);
        $this->cache->save($cacheItem);

        $this->logger->info("Przetworzono linię {$event->getRow()}");
    }
}
