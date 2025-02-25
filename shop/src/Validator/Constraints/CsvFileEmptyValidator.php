<?php

namespace App\Validator\Constraints;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CsvFileEmptyValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CsvFileEmpty) {
            throw new \InvalidArgumentException('Nieprawidłowy typ Constraint');
        }

        if (!$value instanceof UploadedFile) {
            return;
        }

        $filename = $value->getClientOriginalName();

        // Sprawdzamy zawartość pliku (czy wygląda jak CSV)
        if (($handle = fopen($value->getPathname(), 'r')) !== false) {
            $firstLine = fgetcsv($handle);
            fclose($handle);

            if ($firstLine === false || count($firstLine) < 2) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ filename }}', $filename)
                    ->addViolation();
            }
        }
    }

}
