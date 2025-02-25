<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CsvFileEmpty extends Constraint
{
    public string $message = 'The file "{{ filename }}" is empty.';
}
