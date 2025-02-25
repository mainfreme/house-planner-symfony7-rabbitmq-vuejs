<?php

namespace App\Application\CsvProcessing\Handler;

use App\Application\CsvProcessing\Command\UploadCsvFileCommand;
use App\Domain\CsvProcessing\Event\CsvFileUploadedEvent;
use Symfony\Component\Messenger\MessageBusInterface;

class UploadCsvFileHandler
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function __invoke(UploadCsvFileCommand $command)
    {
        $filename = $command->getFilename();

        // Wysyłanie eventu do RabbitMQ
        $this->eventBus->dispatch(new CsvFileUploadedEvent($filename));
    }
}
