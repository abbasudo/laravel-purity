<?php

namespace Abbasudo\LaravelPurity\Filters;


use Abbasudo\LaravelPurity\Filters\Strategies\EqualFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Resolve
{
    // Initialize the private properties of the trait
    private array $fields = [];

    private array $filterStrategies = [
        '$eq' => EqualFilter::class,
    ];

    // apply a single filter to the query builder instance
    public function applyFilter(Builder $query, string $field, array|string $filter)
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
            $callback = $this->filterStrategies[$field]::apply($query, $column, $filter);
            $this->applyRelations($query, $fields, $callback);
        } else {
            // If the field is not recognized as a filter strategy, it is treated as a relation

            foreach ($filter as $subField => $subFilter) {
                $this->fields[] = $field;
                $this->applyFilter($query, $subField, $subFilter);
            }
            $this->fields = [];
        }
    }

    // resolve nested relations if any
    private function applyRelations(Builder $query, array $fields, Closure $callback)
    {
        if (empty($fields)) {
            // If there are no more filterable fields to resolve, apply the closure to the query builder instance
            $callback($query);
        } else {
            // If there are still filterable fields to resolve, apply the closure to a sub-query
            $field = array_shift($fields);
            $query->whereHas($field, function ($subQuery) use ($fields, $callback) {
                $this->applyRelations($subQuery, $fields, $callback);
            });
        }
    }
}
