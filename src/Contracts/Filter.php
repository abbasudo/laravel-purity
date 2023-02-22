<?php

namespace Abbasudo\LaravelPurity\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    /**
     * @param  Builder  $query
     * @param  string  $column
     * @param  array  $filters
     *
     * @return Closure
     */
    public static function apply(Builder $query, string $column, array $filters): Closure;

    /**
     * @return string
     */
    public static function operator(): string;
}
