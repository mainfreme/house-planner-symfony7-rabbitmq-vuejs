<?php

namespace App\Application\FileProcessing;

use App\Domain\FileProcessing\Enum\TextFileAcceptedEnum;

class FileReaderFactory
{
    public function createFor(FilePath $filePath): FileReaderInterface
    {
        $extension = strtolower($filePath->getExtension());

        $fileType = TextFileAcceptedEnum::tryFrom($extension);
        if (!$fileType) {
            throw new UnsupportedFileTypeException("Unsupported file type: $extension");
        }

        return $fileType->createReader();
    }
}
