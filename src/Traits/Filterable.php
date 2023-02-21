<?php

namespace Abbasudo\LaravelPurity\Traits;

use Abbasudo\LaravelPurity\Filters\Resolve;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    // apply filters to the query builder instance
    public function scopeFilter(Builder $query): Builder
    {
        // Retrieve the filters from the request
        $filters = request('filters', []);

        // Apply each filter to the query builder instance
        foreach ($filters as $field => $value) {
            (new Resolve())->applyFilter($query, $field, $value);
        }

        return $query;
    }
}
