<?php

namespace App\Infrastructure\FileProcessing\FileReader;

use App\Infrastructure\FileProcessing\FilePath;
use App\Infrastructure\FileProcessing\FileReaderInterface;
use App\Infrastructure\FileProcessing\FileScanResult;

class PdfReader implements FileReaderInterface
{

    public function read(FilePath $filePath): FileScanResult
    {
        // TODO: Implement read() method.
    }
}
