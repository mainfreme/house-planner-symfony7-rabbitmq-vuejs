<?php

namespace App\Infrastructure\Consumer;

use App\Domain\CsvProcessing\Event\CsvFileUploadedEvent;
use App\Domain\CsvProcessing\Event\CsvRowProcessedEvent;
use Psr\Log\LoggerInterface;
use SplFileObject;
use Symfony\Component\EventDispatcher\EventDispatcher;

class CsvFileConsumer
{
    private static array $separators = [',', ';', '|', "\t"];

    public function __construct(private LoggerInterface $logger, private EventDispatcher $eventDispatcher){}

    public function __invoke(CsvFileUploadedEvent $event)
    {
        $filename = $event->getFilename();
        $filePath = $event->getFilePath();

        $this->logger->info("Rozpoczęto przetwarzanie pliku: {$filename}");

        try {
            $file = new SplFileObject($filePath, 'r');
            $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
            $file->setCsvControl($this->detectSeparator($filePath));
            $file->seek(1);

            $lineNumber = 0;

            foreach ($file as $row) {
                $lineNumber++;

                // Pominięcie pustych linii
                if ($row === [null] || empty($row)) {
                    continue;
                }

                if (count($row) < 2) {
                    $this->logger->warning("Błędny format w linii {$lineNumber}: " . implode(',', $row));
                    continue;
                }

                [$id, $fullName, $email, $name] = $row;

                // dodajmy walidacje maila


                if (!is_numeric($id)) {
                    $this->logger->warning("Nieprawidłowa wartość w linii {$lineNumber}: {$id}");
                    continue;
                }

                [$firstName, $lastName] = explode(" ", $fullName);

                // Tworzymy event dla poprawnej linii
                $csvRowEvent = new CsvRowProcessedEvent($id, $firstName, $lastName, $email, $name, $filename, $row);
                $this->eventDispatcher->dispatch($csvRowEvent);
            }

            $this->logger->info("Zakończono przetwarzanie pliku: {$filename}");

        } catch (\Exception $e) {
            $this->logger->error("Błąd podczas przetwarzania pliku: {$e->getMessage()}");
        }
    }

    private function detectSeparator(string $filePath): string
    {

        $file = new SplFileObject($filePath, 'r');
        $file->setFlags(SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

        $file->seek(1);

        $dataLine = $file->fgets();

        if (!$dataLine) {
            throw new \RuntimeException("Nie udało się odczytać drugiej linii pliku: {$filePath}");
        }

        $separatorCounts = array_map(fn($sep) => substr_count($dataLine, $sep), self::$separators);
        $bestMatch = array_keys($separatorCounts, max($separatorCounts))[0];

        if ($separatorCounts[$bestMatch] === 0) {
            throw new \RuntimeException("Nie udało się wykryć separatora w pliku: {$filePath}");
        }

        return $bestMatch;
    }

}
