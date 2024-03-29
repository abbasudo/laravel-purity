<?php

namespace Abbasudo\Purity\Sorts\Strategies;

use Abbasudo\Purity\Sorts\SortAbstract;
use Illuminate\Database\Eloquent\Builder;

class DefaultSort extends SortAbstract
{
    public function apply(): Builder
    {
        return $this->query->orderByRaw("$this->column $this->direction");
    }
}
