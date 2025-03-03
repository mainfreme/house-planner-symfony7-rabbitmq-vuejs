<?php

namespace App\Domain\CsvProcessing\Enum;

enum CsvFileUploadStatusEnum: string
{
    case SENDING = 'sending';
    case COPIED = 'copied';
    case PENDING = 'pending';
    case PROCESSED = 'processed';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::SENDING => 'Wysyłam ...',
            self::COPIED => 'Skopiowane',

            self::PENDING => 'Oczekujące',
            self::PROCESSED => 'Zatwierdzone',
            self::FAILED => 'Odrzucone',
        };
    }
}
