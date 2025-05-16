<?php

namespace App\Application\FileProcessing;

class FileScanService
{
    public function __construct(
        private readonly FileReaderFactory $fileReaderFactory,
    ) {}

    public function scan(FilePath $filePath): FileScanResult
    {
        $reader = $this->fileReaderFactory->createFor($filePath);
        return $reader->read($filePath);
    }

}
