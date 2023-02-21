<?php

namespace Abbasudo\LaravelPurity\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    public static function apply(Builder $query, string $column, array $filters): Closure;
}
