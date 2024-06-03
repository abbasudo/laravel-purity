<?php

namespace Abbasudo\Purity\Tests\App\Filters;

use Abbasudo\Purity\Filters\FilterList;
use Abbasudo\Purity\Filters\Resolve;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CustomFilterResolver extends Resolve
{
    public function __construct(FilterList $filterList, Model $model)
    {
        parent::__construct($filterList, $model);
    }

    public function apply(Builder $query, string $field, array|string $values): void
    {
        // do some custom logic
        if (isset($values['$pure']) && $values['$pure'] === 'true') {
            parent::apply($query, $field, ['$startsWith' => 'pure_']);

            return;
        }

        parent::apply($query, $field, $values);
    }
}
