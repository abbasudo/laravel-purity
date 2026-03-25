<?php

namespace Abbasudo\Purity\Filters\Strategies;

use Abbasudo\Purity\Filters\Filter;
use Closure;

class ContainsFilter extends Filter
{
    /**
     * Operator string to detect in the query params.
     */
    protected static string $operator = '$contains';

    /**
     * Apply filter logic to $query.
     */
    public function apply(): Closure
    {
        return function ($query) {
            foreach ($this->values as $value) {
                $query->where($this->column, 'like', "%{$value}%");
            }
        };
    }
}
