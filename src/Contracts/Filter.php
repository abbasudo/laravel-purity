<?php

namespace Abbasudo\Purity\Contracts;

use Closure;

interface Filter
{
    /**
     * @return string|null
     */
    public static function operator(): string|null;

    /**
     * @return Closure
     */
    public function apply(): Closure;
}
