<?php

namespace App\Validator\Constraints;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CsvFileEmptyValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
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
