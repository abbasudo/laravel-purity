<?php

namespace Abbasudo\Purity\Contracts;

use Closure;

interface Filter
{
    /**
     * @return string
     */
    public static function operator(): string;

    /**
     * @return Closure
     */
    public function apply(): Closure;
}
