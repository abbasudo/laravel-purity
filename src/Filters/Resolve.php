<?php

namespace Abbasudo\LaravelPurity\Filters;


use Abbasudo\LaravelPurity\Contracts\Filter as FilterContract;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Resolve
{
    /**
     * List of relations and the column
     *
     * @var array
     */
    private array $fields = [];

    /**
     * List of available filters
     *
     * @var array|FilterContract[]
     */
    private array $filterStrategies;

    /**
     * @param  array|FilterContract[]  $filters
     */
    public function __construct(array $filters)
    {
        foreach ($filters as $filter) {
            $this->filterStrategies[$filter::operator()] = $filter;
        }
    }

    /**
     * @param  Builder  $query
     * @param  string  $field
     * @param  array  $filter
     *
     * @return void
     */
    private function applyRelationFilter(Builder $query, string $field, array $filter): void
    {
        foreach ($filter as $subField => $subFilter) {
            $this->fields[] = $field;
            $this->applyFilter($query, $subField, $subFilter);
        }
        array_pop($this->fields);
    }

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
            $this->applyFilterStrategy($query, $field, $filter);
        } else {
            // If the field is not recognized as a filter strategy, it is treated as a relation
            $this->applyRelationFilter($query, $field, $filter);
        }
    }

    /**
     * @param  Builder  $query
     * @param  string  $field
     * @param  array  $filter
     *
     * @return void
     */
    private function applyFilterStrategy(Builder $query, string $field, array $filter): void
    {
        $callback = $this->filterStrategies[$field]::apply($query, end($this->fields), $filter);
        $this->filterRelations($query, $callback);
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

        $this->applyRelations($query, $callback);
    }

    /**
     * Resolve nested relations if any
     *
     * @param  Builder  $query
     * @param  Closure  $callback
     *
     * @return void
     */
    private function applyRelations(Builder $query, Closure $callback): void
    {
        if (empty($this->fields)) {
            // If there are no more filterable fields to resolve, apply the closure to the query builder instance
            $callback($query);
        } else {
            // If there are still filterable fields to resolve, apply the closure to a sub-query
            $field = array_shift($this->fields);
            $query->whereHas($field, function ($subQuery) use ($callback) {
                $this->applyRelations($subQuery, $callback);
            });
        }
    }
}
