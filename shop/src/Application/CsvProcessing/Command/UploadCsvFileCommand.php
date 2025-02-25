<?php

namespace App\Application\CsvProcessing\Command;

class UploadCsvFileCommand
{
    public function __construct(private string $filename, private string $filePath)
    {
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }
}
