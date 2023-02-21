<?php

namespace Abbasudo\LaravelPurity\Traits;

use Abbasudo\LaravelPurity\Filters\Strategies\EqualFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    // Initialize the private properties of the trait
    private array $fields = [];

    private array $filterStrategies = [
        '$eq' => EqualFilter::class,
    ];

    // apply filters to the query builder instance
    public function scopeFilter(Builder $query): Builder
    {
        // Retrieve the filters from the request
        $filters = request('filters', []);

        // Apply each filter to the query builder instance
        foreach ($filters as $field => $value) {
            $this->applyFilter($query, $field, $value);
        }

        return $query;
    }

    // apply a single filter to the query builder instance
    private function applyFilter(Builder $query, string $field, array|string $filter)
    {
        // Ensure that the filter is an array
        if ( ! is_array($filter)) {
            $filter = [$filter];
        }

        // get the previous filterable fields (empty on the first execution)
        $fields = $this->fields;

        // Determine the column to which the filter applies
        $column = array_pop($fields);

        // Resolve the filter using the appropriate strategy
        if (isset($this->filterStrategies[$field])) {
            $strategy = new $this->filterStrategies[$field]($query, $column, $filter);
            $this->resolveRelations($query, $fields, $strategy->apply());
        } else {
            // If the field is not recognized as a filter strategy, it is treated as a relation
            $fields[] = $field;
            foreach ($filter as $subField => $subFilter) {
                $this->fields[] = $field;
                $this->applyFilter($query, $subField, $subFilter);
            }
            $this->fields = [];
        }
    }

    // resolve nested relations if any
    private function resolveRelations(Builder $query, array $fields, Closure $callback)
    {
        if (empty($fields)) {
            // If there are no more filterable fields to resolve, apply the closure to the query builder instance
            $callback($query);
        } else {
            // If there are still filterable fields to resolve, apply the closure to a sub-query
            $field = array_shift($fields);
            $query->whereHas($field, function ($subQuery) use ($fields, $callback) {
                $this->resolveRelations($subQuery, $fields, $callback);
            });
        }
    }
}
