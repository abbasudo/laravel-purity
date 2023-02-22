<?php

namespace Abbasudo\LaravelPurity\Filters;


use Abbasudo\LaravelPurity\Contracts\Filter as FilterContract;
use Abbasudo\LaravelPurity\Filters\Strategies\EqualFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Resolve
{
    private array $fields = [];

    /**
     * List of available filters
     *
     * @var array|FilterContract[]
     */
    private array $filterStrategies = [
        '$eq' => EqualFilter::class,
    ];

    /**
     * Apply a single filter to the query builder instance
     *
     * @param  Builder  $query
     * @param  string  $field
     * @param  array|string  $filter
     *
     * @return void
     */
    public function applyFilter(Builder $query, string $field, array|string $filter): void
    {
        // Ensure that the filter is an array
        if ( ! is_array($filter)) {
            $filter = [$filter];
        }

        // Resolve the filter using the appropriate strategy
        if (isset($this->filterStrategies[$field])) {
            //call apply method of the appropriate filter class
            $callback = $this->filterStrategies[$field]::apply($query, end($this->fields), $filter);

            $this->filterRelations($query, $callback);
        } else {
            // If the field is not recognized as a filter strategy, it is treated as a relation

            foreach ($filter as $subField => $subFilter) {
                $this->fields[] = $field;
                $this->applyFilter($query, $subField, $subFilter);
            }
            $this->fields = [];
        }
    }

    /**
     * @param  Builder  $query
     * @param  Closure  $callback
     *
     * @return void
     */
    private function filterRelations(Builder $query, Closure $callback): void
    {
        array_pop($this->fields);

        $this->applyRelations($query, $this->fields, $callback);
    }

    /**
     * Resolve nested relations if any
     *
     * @param  Builder  $query
     * @param  array  $fields
     * @param  Closure  $callback
     *
     * @return void
     */
    private function applyRelations(Builder $query, array $fields, Closure $callback): void
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
