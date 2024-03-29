<?php

namespace Abbasudo\Purity\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class SortAbstract
{
    public function __construct(
        protected string $column,
        protected string $direction,
        protected Builder $query,
        protected ?Model $model = null,
        protected ?string $relationName = null
    ) {
    }

    abstract public function apply(): Builder;
}
