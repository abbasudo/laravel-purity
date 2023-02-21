<?php

namespace Abbasudo\LaravelPurity\Filters\Strategies;

use Abbasudo\LaravelPurity\Contracts\Filter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class EqualFilter implements Filter
{
    public static function apply(Builder $query, string $column, array $filters): Closure
    {
        return function ($query) use ($column, $filters) {
            foreach ($filters as $filter) {
                $query->where($column, $filter);
            }
        };
    }
}
