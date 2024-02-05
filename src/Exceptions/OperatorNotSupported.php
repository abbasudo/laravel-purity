<?php

namespace Abbasudo\Purity\Exceptions;

use InvalidArgumentException;

class OperatorNotSupported extends InvalidArgumentException
{
    public static function create(string $field, string $operator, array $supportedOperators)
    {
        return new static(
            "The operator {$operator} is not supported for the field {$field} supported operators : "
            . implode(', ', $supportedOperators)
        );
    }
}
