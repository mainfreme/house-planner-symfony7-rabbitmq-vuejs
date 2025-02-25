<?php

namespace App\Application\CsvProcessing\Handler;

use App\Application\CsvProcessing\Command\UploadCsvFileCommand;
use App\Domain\CsvProcessing\Event\CsvFileUploadedEvent;
use Symfony\Component\Messenger\MessageBusInterface;

class UploadCsvFileHandler
{

    public function __construct(private MessageBusInterface $eventBus)
    {}

    public function __invoke(UploadCsvFileCommand $command): void
    {
        $filename = $command->getFilename();
        $filePath = $command->getFilePath();

        $this->eventBus->dispatch(new CsvFileUploadedEvent($filename, $filePath));
    }
}
