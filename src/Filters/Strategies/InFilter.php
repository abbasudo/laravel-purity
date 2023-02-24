<?php

namespace Abbasudo\Purity\Filters\Strategies;

use Abbasudo\Purity\Contracts\Filter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class InFilter implements Filter
{
    /**
     * @param  Builder  $query
     * @param  string  $column
     * @param  array  $filters
     *
     * @return Closure
     */
    public static function apply(Builder $query, string $column, array $filters): Closure
    {
        return function ($query) use ($column, $filters) {
            $query->whereIn($column, $filters);
        };
    }

    /**
     * @return string
     */
    public static function operator(): string
    {
        return '$in';
    }
}
