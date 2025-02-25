<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CsvFileValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {

        if (!$constraint instanceof CsvFile) {
            throw new \InvalidArgumentException('Nieprawidłowy typ Constraint');
        }
        if (!$value instanceof UploadedFile) {
            return;
        }

        $filename = $value->getClientOriginalName();
        $mimeType = $value->getMimeType();
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Sprawdzamy poprawność rozszerzenia i nazwy pliku
        if ($extension !== 'csv' || !str_ends_with(strtolower($filename), '.csv')) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ filename }}', $filename)
                ->addViolation();
            return;
        }

        // Sprawdzamy nagłówek MIME
        if (!in_array($mimeType, ['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel'])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ filename }}', $filename)
                ->addViolation();
        }
    }
}
