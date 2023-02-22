<?php

namespace Abbasudo\Purity\Exceptions;

use InvalidArgumentException;

class NoOperatorMatch extends InvalidArgumentException
{
    public static function create(array $filters)
    {
        return new static(
            "No operator matches your request. expecting one of thies operators : ".implode(',', $filters)
        );
    }
}
