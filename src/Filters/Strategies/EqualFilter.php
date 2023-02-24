<?php

namespace Abbasudo\Purity\Filters\Strategies;

use Abbasudo\Purity\Filters\Filter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class EqualFilter extends Filter
{
    public static string $operator = '$eq';

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
            foreach ($filters as $filter) {
                $query->where($column, $filter);
            }
        };
    }

    /**
     * @return string
     */
    public static function operator(): string
    {
        return '$eq';
    }
}
