<?php

namespace Abbasudo\Purity\Exceptions;

use InvalidArgumentException;

class FieldNotSupported extends InvalidArgumentException
{
    public static function create(array $fields)
    {
        return new static(
            'The field you trying to filter is not supported. Supported fields : '.implode(', ', $fields)
        );
    }
}
