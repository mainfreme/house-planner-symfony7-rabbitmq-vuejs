<?php

namespace App\Application\CsvProcessing\EventListener;

use App\Domain\CsvProcessing\Event\CsvRowProcessedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class CsvRowProcessedEventHandler
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    #[AsEventListener(event: CsvRowProcessedEvent::class)]
    public function onCsvRowProcessed(CsvRowProcessedEvent $event): void
    {
        $this->logger->info("Obsługa wiersza CSV z pliku: {$event->getFilename()}, Linia: {$event->getRow()}");

        $data = $event->getData();

        // Tutaj możemy zwalidować dane i zapisywać dane do bazy

        $this->logger->info("Przetworzono linię {$event->getRow()}");
    }
}
