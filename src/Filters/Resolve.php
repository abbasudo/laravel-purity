<?php

namespace Abbasudo\Purity\Filters;

use Abbasudo\Purity\Exceptions\FieldNotSupported;
use Abbasudo\Purity\Exceptions\NoOperatorMatch;
use Abbasudo\Purity\Exceptions\OperatorNotSupported;
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

    private array $previousModels = [];

    /**
     * @param FilterList $filterList
     * @param Model      $model
     */
    public function __construct(FilterList $filterList, Model $model)
    {
        $this->filterList = $filterList;
        $this->model = $model;
    }

    /**
     * @param Builder      $query
     * @param string       $field
     * @param array|string $values
     *
     * @throws Exception
     * @throws Exception
     *
     * @return void
     */
    public function apply(Builder $query, string $field, array|string $values): void
    {
        if (!$this->safe(fn () => $this->validate([$field => $values]))) {
            return;
        }

        $this->filter($query, $field, $values);
    }

    /**
     * run functions with or without exception.
     *
     * @param Closure $closure
     *
     * @throws Exception
     * @throws Exception
     *
     * @return bool
     */
    private function safe(Closure $closure): bool
    {
        try {
            $closure();

            return true;
        } catch (Exception $exception) {
            if (config('purity.silent')) {
                return false;
            }

            throw $exception;
        }
    }

    /**
     * @param array|string $values
     *
     * @return void
     */
    private function validate(array|string $values = [])
    {
        if (empty($values) || is_string($values)) {
            throw NoOperatorMatch::create($this->filterList->keys());
        }

        if (!in_array(key($values), $this->filterList->keys())) {
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
     * @throws Exception
     * @throws Exception
     *
     * @return void
     */
    private function filter(Builder $query, string $field, array|string|null $filters): void
    {
        // Ensure that the filter is an array
        $filters = is_array($filters) ? $filters : [$filters];

        // If $field is an operator name
        if ($this->filterList->get($field) !== null) {
            $this->safe(fn () => $this->applyFilterStrategy($query, $field, $filters));
            return;
        }

        // If the value array is operator-first, treat $field as a column
        $firstKey = array_key_first($filters);
        if ($firstKey !== null && $this->filterList->get($firstKey) !== null) {
            // preserve current relation path for each operator application
            $path = $this->fields;

            foreach ($filters as $operator => $opFilters) {
                // rebuild the full path (relations + column) so whereHas gets applied correctly
                $this->fields = array_merge($path, [$this->model->getField($field)]);

                $this->safe(fn () => $this->applyFilterStrategy(
                    $query,
                    $operator,
                    is_array($opFilters) ? $opFilters : [$opFilters]
                ));
            }

            // restore path for subsequent processing
            $this->fields = $path;
            return;
        }

        // Otherwise treat as relation
        $this->safe(fn () => $this->applyRelationFilter($query, $field, $filters));
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

        $callback = (new $filter($query, $field, $filters))->apply();

        $this->filterRelations($query, $callback);
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
        // remove the last field until its empty
        $field = array_shift($this->fields);
        $query->whereHas($field, fn ($subQuery) => $this->applyRelations($subQuery, $callback));
    }

    /**
     * @param Builder $query
     * @param string  $field
     * @param array   $filters
     *
     * @throws Exception
     *
     * @return void
     */
    private function applyRelationFilter(Builder $query, string $field, array $filters): void
    {
        // validate relation on the current model
        $this->validateField($field);

        // push relation into the path, then advance the model to that relation
        $this->fields[] = $this->model->getField($field);
        $this->prepareModelForRelation();

        // handle all subfields under this relation
        foreach ($filters as $subField => $subFilter) {
            $this->filter($query, $subField, $subFilter);
        }

        // restore back to the parent model and pop the relation from the path
        $this->restorePreviousModel();
    }

    private function prepareModelForRelation(): void
    {
        $relation = end($this->fields);
        if ($relation !== false) {
            $this->previousModels[] = $this->model;
            $this->model = $this->model->$relation()->getRelated();
        }
    }

    private function restorePreviousModel(): void
    {
        array_pop($this->fields);
        if (!empty($this->previousModels)) {
            $this->model = array_pop($this->previousModels);
        }
    }

    /**
     * @param string $field
     *
     * @return void
     */
    private function validateField(string $field): void
    {
        $availableFields = $this->model->availableFields();

        if (!in_array($field, $availableFields)) {
            throw FieldNotSupported::create($field, $this->model::class, $availableFields);
        }
    }

    /**
     * @param string $field
     * @param string $operator
     *
     * @return void
     */
    private function validateOperator(string $field, string $operator): void
    {
        $availableFilters = $this->model->getAvailableFiltersFor($field);

        if (!$availableFilters || in_array($operator, $availableFilters)) {
            return;
        }

        throw OperatorNotSupported::create($field, $operator, $availableFilters);
    }
}
