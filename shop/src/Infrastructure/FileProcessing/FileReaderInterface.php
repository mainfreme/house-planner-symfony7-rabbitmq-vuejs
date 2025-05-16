<?php

namespace App\Infrastructure\FileProcessing;

interface FileReaderInterface
{
    public function read(FilePath $filePath): FileScanResult;
}
