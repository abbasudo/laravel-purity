<?php

namespace Abbasudo\Purity\Filters\Strategies;

use Abbasudo\Purity\Filters\Filter;
use Closure;

class EqualFilter extends Filter
{
    public static string $operator = '$eq';

    /**
     * @return Closure
     */
    public function apply(): Closure
    {
        return function ($query) {
            foreach ($this->filters as $filter) {
                $query->where($this->column, $filter);
            }
        };
    }
}
