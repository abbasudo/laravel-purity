<?php

namespace Abbasudo\Purity\Filters\Strategies;

use Abbasudo\Purity\Filters\Filter;
use Closure;

class InFilter extends Filter
{
    /**
     * Operator to detect in the query params.
     *
     * @var string
     */
    protected static string $operator = '$in';

    /**
     * Apply filter logic to $query.
     *
     * @return Closure
     */
    public function apply(): Closure
    {
        return function ($query) {
            $query->whereIn($this->column, $this->filters);
        };
    }

}
