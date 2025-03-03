<?php

namespace App\Application\CsvProcessing\Handler;

use App\Application\CsvProcessing\Command\UploadCsvFileCommand;
use App\Domain\CsvProcessing\Event\CsvFileUploadedEvent;
use Symfony\Component\Messenger\MessageBusInterface;

class UploadCsvFileHandler
{
    public function __construct(
        private MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(UploadCsvFileCommand $command): void
    {
        $this->eventBus->dispatch(new CsvFileUploadedEvent($command->getFilename(), $command->getFilePath(), $command->getUuid()));
    }
}
