<?php

namespace Abbasudo\LaravelPurity\Filters\Strategies;

use Abbasudo\LaravelPurity\Contracts\Filter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class EqualFilter implements Filter
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
            foreach ($filters as $filter) {
                $query->where($column, $filter);
            }
        };
    }
}
