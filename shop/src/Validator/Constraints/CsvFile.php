<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CsvFile extends Constraint
{
    public string $message = 'The file "{{ filename }}" is not a valid CSV file.';
}
