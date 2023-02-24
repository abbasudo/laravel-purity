<?php

namespace Abbasudo\Purity\Filters\Strategies;

use Abbasudo\Purity\Filters\Filter;
use Closure;

class InFilter extends Filter
{
    protected static string $operator = '$in';

    /**
     * @return Closure
     */
    public function apply(): Closure
    {
        return function ($query) {
            $query->whereIn($this->column, $this->filters);
        };
    }

}
