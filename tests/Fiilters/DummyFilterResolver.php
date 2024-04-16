<?php

namespace Abbasudo\Purity\Tests\Fiilters;

use Abbasudo\Purity\Filters\FilterList;
use Abbasudo\Purity\Filters\Resolve;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DummyFilterResolver extends Resolve
{
    public function __construct(FilterList $filterList, Model $model)
    {
        parent::__construct($filterList, $model);
    }

    public function apply(Builder $query, string $field, array|string $values): void
    {
      $this->dummyFilter($query, $field, $values);
    }

    public function dummyFilter(Builder $query, string $field, array|string $values): void
    {
        // does nothing
    }
}
