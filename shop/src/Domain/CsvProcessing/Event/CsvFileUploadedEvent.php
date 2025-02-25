<?php

namespace App\Domain\CsvProcessing\Event;


class CsvFileUploadedEvent
{
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }
}
