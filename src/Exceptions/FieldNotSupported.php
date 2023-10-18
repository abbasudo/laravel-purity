<?php

namespace Abbasudo\Purity\Exceptions;

use InvalidArgumentException;

class FieldNotSupported extends InvalidArgumentException
{
    public static function create(string $field, string $model, array $supported)
    {
        return new static(
            "The field '$field' is not supported for model $model. Supported fields : " . implode(', ', $supported)
        );
    }
}
