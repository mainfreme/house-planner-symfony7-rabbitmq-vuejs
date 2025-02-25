<?php

namespace App\Infrastructure\Consumer;

use App\Domain\CsvProcessing\Event\CsvFileUploadedEvent;
use App\Domain\CsvProcessing\Event\CsvRowProcessedEvent;
use http\Exception\RuntimeException;
use Psr\Log\LoggerInterface;
use SplFileObject;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class CsvFileConsumer
{
    /**
     * @var array|string[]
     */
    private static array $separators = [',', ';', '|', "\t"];

    public function __construct(private LoggerInterface $logger, private EventDispatcher $eventDispatcher){}

    public function __invoke(CsvFileUploadedEvent $event): void
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
                if ($row === [null] || is_string($row) || $row === false || count($row) < 4) {
                    continue;
                }

                [$id, $fullName, $email, $name] = array_pad($row, 4, null);

                // dodajmy walidacje maila


                if (!is_numeric($id)) {
                    $this->logger->warning(sprintf("Nieprawidłowa wartość w linii %s", $lineNumber));
                    continue;
                }

                // Tworzymy event dla poprawnej linii
                $csvRowEvent = new CsvRowProcessedEvent($id, $fullName, $email, $name, $filename, $lineNumber);
                $this->eventDispatcher->dispatch($csvRowEvent);
            }

            $this->logger->info("Zakończono przetwarzanie pliku: {$filename}");

        } catch (\Exception $e) {
            $this->logger->error("Błąd podczas przetwarzania pliku: {$e->getMessage()}");
        }
    }

    public static function detectSeparator(string $filePath): string
    {
        $file = new SplFileObject($filePath, 'r');
        $file->setFlags(SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);

        // Przechodzimy do drugiej linii (indeksowanie od 0)
        $file->seek(1);
        $dataLine = $file->current();

        if (!is_string($dataLine) || trim($dataLine) === '') {
            throw new RuntimeException("Nie udało się odczytać drugiej linii pliku: {$filePath}");
        }

        // Zliczamy wystąpienia separatorów
        /** @var array<string, int> $separatorCounts */
        $separatorCounts = [];
        foreach (self::$separators as $separator) {
            $count = substr_count($dataLine, $separator);
            if ($count > 0) {
                $separatorCounts[$separator] = $count;
            }
        }

        // Jeśli nie znaleziono żadnego separatora, zgłoś wyjątek
        if ($separatorCounts === []) {
            throw new RuntimeException("Nie udało się wykryć separatora w pliku: {$filePath}");
        }

        // Znalezienie separatora z największą liczbą wystąpień
        /** @var string $maxSeparator */
        $maxSeparator = array_search(max($separatorCounts), $separatorCounts, true);

        return $maxSeparator;
    }

}
