<?php

namespace App\Application\CsvProcessing\Command;

class UploadCsvFileCommand
{
    public function __construct(
        private string $filename,
        private string $filePath,
        private string $uuid
    ) {
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}
