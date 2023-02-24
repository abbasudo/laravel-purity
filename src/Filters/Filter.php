<?php

namespace Abbasudo\Purity\Filters;

abstract class Filter implements \Abbasudo\Purity\Contracts\Filter
{
    /**
     * @return string
     */
    public static function operator(): string
    {
        return static::$operator;
    }
}
