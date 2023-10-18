<?php

namespace Abbasudo\Purity\Filters;

use Abbasudo\Purity\Exceptions\FieldNotSupported;
use Abbasudo\Purity\Exceptions\NoOperatorMatch;
use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Resolve
{
    /**
     * List of relations and the column.
     *
     * @var array
     */
    private array $fields = [];

    /**
     * List of available filters.
     *
     * @var filterList
     */
    private FilterList $filterList;

    private Model $model;

    /**
     * @param FilterList $filterList
     * @param Model      $model
     */
    public function __construct(FilterList $filterList, Model $model)
    {
        $this->filterList = $filterList;
        $this->model      = $model;
    }

    /**
     * @param Builder      $query
     * @param string       $field
     * @param array|string $values
     *
     * @return void
     * @throws Exception
     *
     */
    public function apply(Builder $query, string $field, array|string $values): void
    {
        if (!$this->safe(fn() => $this->validate([$field => $values]))) {
            return;
        }

        $this->filter($query, $field, $values);
    }

    /**
     * run functions with or without exception.
     *
     * @param Closure $closure
     *
     * @return bool
     * @throws Exception
     *
     */
    private function safe(Closure $closure): bool
    {
        try {
            $closure();

            return true;
        } catch (Exception $exception) {
            if (config('purity.silent')) {
                return false;
            } else {
                throw $exception;
            }
        }
    }

    /**
     * @param array|string $values
     *
     * @return void
     */
    private function validate(array|string $values = [])
    {
        if (empty($values) or is_string($values)) {
            throw NoOperatorMatch::create($this->filterList->keys());
        }

        if (in_array(key($values), $this->filterList->keys())) {
            return;
        } else {
            $this->validate(array_values($values)[0]);
        }
    }

    /**
     * Apply a single filter to the query builder instance.
     *
     * @param Builder           $query
     * @param string            $field
     * @param array|string|null $filters
     *
     * @return void
     * @throws Exception
     *
     */
    private function filter(Builder $query, string $field, array|string|null $filters): void
    {
        // Ensure that the filter is an array
        if (!is_array($filters)) {
            $filters = [$filters];
        }

        // Resolve the filter using the appropriate strategy
        if ($this->filterList->get($field) !== null) {
            //call apply method of the appropriate filter class
            $this->safe(fn() => $this->applyFilterStrategy($query, $field, $filters));
        } else {
            // If the field is not recognized as a filter strategy, it is treated as a relation
            $this->safe(fn() => $this->applyRelationFilter($query, $field, $filters));
        }
    }

    /**
     * @param Builder $query
     * @param string  $operator
     * @param array   $filters
     *
     * @return void
     */
    private function applyFilterStrategy(Builder $query, string $operator, array $filters): void
    {
        $filter = $this->filterList->get($operator);

        $field = end($this->fields);

        $this->validateField($field);

        $callback = (new $filter($query, $field, $filters))->apply();

        $this->filterRelations($query, $callback);
    }

    private function validateField(string $field): void
    {
        $availableFields = $this->model->availableFields();

        if (!array_search($field, $availableFields)) {
            throw FieldNotSupported::create($availableFields);
        }
    }

    /**
     * @param Builder $query
     * @param Closure $callback
     *
     * @return void
     */
    private function filterRelations(Builder $query, Closure $callback): void
    {
        array_pop($this->fields);

        $this->applyRelations($query, $callback);
    }

    /**
     * Resolve nested relations if any.
     *
     * @param Builder $query
     * @param Closure $callback
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
     * @param Builder $query
     * @param Closure $callback
     *
     * @return void
     */
    private function relation(Builder $query, Closure $callback)
    {
        // remove last field until its empty
        $field = array_shift($this->fields);
        $query->whereHas($field, function ($subQuery) use ($callback) {
            $this->applyRelations($subQuery, $callback);
        });
    }

    /**
     * @param Builder $query
     * @param string  $field
     * @param array   $filters
     *
     * @return void
     * @throws Exception
     *
     */
    private function applyRelationFilter(Builder $query, string $field, array $filters): void
    {
        foreach ($filters as $subField => $subFilter) {
            $this->fields[] = $field;
            $this->filter($query, $subField, $subFilter);
        }
        array_pop($this->fields);
    }
}
