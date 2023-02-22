<?php

namespace Abbasudo\LaravelPurity\Filters;


use Abbasudo\LaravelPurity\Contracts\Filter as FilterContract;
use Closure;
use Exception;
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
     * @param  array  $filters
     *
     * @return void
     * @throws Exception
     */
    private function applyRelationFilter(Builder $query, string $field, array $filters): void
    {
        foreach ($filters as $subField => $subFilter) {
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
     * @param  array|string|null  $filters
     *
     * @return void
     * @throws Exception
     */
    public function applyFilter(Builder $query, string $field, array|string|null $filters): void
    {
        // Ensure that the filter is an array
        if ( ! is_array($filters)) {
            $filters = [$filters];
        }

        // Resolve the filter using the appropriate strategy
        if (isset($this->filterStrategies[$field])) {
            //call apply method of the appropriate filter class
            $this->safe(fn() => $this->applyFilterStrategy($query, $field, $filters));
        } else {
            // If the field is not recognized as a filter strategy, it is treated as a relation
            $this->safe(fn() => $this->applyRelationFilter($query, $field, $filters));
        }
    }

    /**
     * run functions with or without exception
     *
     * @param  Closure  $param
     *
     * @return void
     * @throws Exception
     */
    private function safe(Closure $param): void
    {
        try {
            $param();
        } catch (Exception $exception) {
            if (config('purity.silent')) {
                return;
            } else {
                throw $exception;
            }
        }
    }

    /**
     * @param  Builder  $query
     * @param  string  $field
     * @param  array  $filters
     *
     * @return void
     */
    private function applyFilterStrategy(Builder $query, string $field, array $filters): void
    {
        $callback = $this->filterStrategies[$field]::apply($query, end($this->fields), $filters);

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
            $this->relation($query, $callback);
        }
    }

    /**
     * @param  Builder  $query
     * @param  Closure  $callback
     *
     * @return void
     */
    private function relation(Builder $query, Closure $callback)
    {
        // remove last field till its empty
        $field = array_shift($this->fields);
        $query->whereHas($field, function ($subQuery) use ($callback) {
            $this->applyRelations($subQuery, $callback);
        });
    }
}
