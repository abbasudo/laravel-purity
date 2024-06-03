<?php

namespace Abbasudo\Purity\Traits;

use Abbasudo\Purity\Filters\FilterList;
use Abbasudo\Purity\Filters\Resolve;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * List of available filters, can be set on the model otherwise it will be read from config.
 *
 * @property array $filters
 *
 * List of available fields, if not declared will accept every thing.
 * @property array $filterFields
 *
 * Fields will restrict to defined filters.
 * @property array $restrictedFilters
 * @property array $renamedFilterFields
 * @property array $userDefinedFilterFields;
 * @property array $sanitizedRestrictedFilters;
 */
trait Filterable
{
    use getColumns;

    private string $defaultFilterResolverClass = Resolve::class;

    /**
     * Returns full class name of the filter resolver.
     * Can be overridden in the model.
     *
     * @return string
     */
    protected function getFilterResolver(): string
    {
        return $this->defaultFilterResolverClass;
    }

    /**
     * Apply filters to the query builder instance.
     *
     * @param Builder    $query
     * @param array|null $params
     *
     * @throws Exception
     *
     * @return Builder
     */
    public function scopeFilter(Builder $query, array|null $params = null): Builder
    {
        $this->bootFilter();

        if (!isset($params)) {
            // Retrieve the filters from the request query
            $params = request()->query('filters', []);
        }

        // Apply each filter to the query builder instance

        foreach ($params as $field => $value) {
            $value = $this->formatValuesToNumericAndBoolean($value);
            app($this->getFilterResolver())->apply($query, $field, $value);
        }

        return $query;
    }

    /**
     * Convert numeric values in the given array to boolean and floats recursively.
     *
     * @param mixed $value
     * 
     * @return mixed
     */
    private function formatValuesToNumericAndBoolean($value)
    {
        return array_map(function ($item) {
            if (is_array($item)) {
                return $this->formatValuesToNumericAndBoolean($item);
            }

            if (is_numeric($item)) {
                return floatval($item);
            }

            if (is_string($item)) {
                $lowerItem = strtolower($item);
                if ($lowerItem === 'true') {
                    return true;
                }

                if ($lowerItem === 'false') {
                    return false;
                }
            }

            return $item;
        }, $value);
    }

    /**
     * boots filter bindings.
     *
     * @return void
     */
    private function bootFilter(): void
    {
        app()->singleton(FilterList::class, function () {
            return (new FilterList())->only($this->getFilters());
        });

        app()->when($this->getFilterResolver())->needs(Model::class)->give(fn () => $this);
    }

    /**
     * @return array
     */
    private function getFilters(): array
    {
        return $this->filters ?? config('purity.filters');
    }

    /**
     * @param Builder      $query
     * @param array|string $filters
     *
     * @return Builder
     */
    public function scopeFilterBy(Builder $query, array|string $filters): Builder
    {
        $this->filters = is_array($filters) ? $filters : array_slice(func_get_args(), 1);

        return $query;
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function getField(string $field): string
    {
        return $this->realName(($this->renamedFilterFields ?? []) + $this->availableFields(), $field);
    }

    /**
     * @return array
     */
    public function availableFields(): array
    {
        if (!isset($this->filterFields) && !isset($this->renamedFilterFields)) {
            return $this->getDefaultFields();
        }

        return $this->getUserDefinedFilterFields();
    }

    private function getDefaultFields(): array
    {
        return array_merge($this->getTableColumns(), $this->relations());
    }

    /**
     * Get formatted fields from filterFields.
     *
     * @return array
     */
    private function getUserDefinedFilterFields(): array
    {
        if (isset($this->userDefinedFilterFields)) {
            return $this->userDefinedFilterFields;
        }

        if (isset($this->renamedFilterFields, $this->filterFields)) {
            $fields = $this->getFilterFields();
            $filterFields = [];

            foreach ($fields as $filterName) {
                if ($columnName = array_search($filterName, $this->renamedFilterFields)) {
                    $filterFields[$columnName] = $filterName;
                } else {
                    $filterFields[] = $filterName;
                }
            }

            return $this->userDefinedFilterFields = $filterFields;
        }

        if (isset($this->renamedFilterFields)) {
            $fields = $this->getDefaultFields();

            $filterFields = [];

            foreach ($fields as $filterName) {
                if (array_key_exists($filterName, $this->renamedFilterFields)) {
                    $filterFields[$filterName] = $this->renamedFilterFields[$filterName];
                } else {
                    $filterFields[] = $filterName;
                }
            }

            return $this->userDefinedFilterFields = $filterFields;
        }

        return $this->userDefinedFilterFields = $this->getFilterFields();
    }

    private function getFilterFields(): array
    {
        $userDefinedFilterFields = [];

        foreach ($this->filterFields as $key => $value) {
            if (is_int($key)) {
                if (Str::contains($value, ':')) {
                    $userDefinedFilterFields[] = str($value)->before(':')->squish()->toString();
                } else {
                    $userDefinedFilterFields[] = $value;
                }
            } else {
                $userDefinedFilterFields[] = $key;
            }
        }

        return $userDefinedFilterFields;
    }

    /**
     * @return array<int, string>
     */
    public function getRestrictedFilters(): array
    {
        if (isset($this->sanitizedRestrictedFilters)) {
            return $this->sanitizedRestrictedFilters;
        }

        $restrictedFilters = [];

        foreach ($this->restrictedFilters ?? $this->filterFields ?? [] as $key => $value) {
            if (is_int($key) && Str::contains($value, ':')) {
                $tKey = str($value)->before(':')->squish()->toString();
                $tValue = str($value)->after(':')->squish()->explode(',')->all();
                $restrictedFilters[$tKey] = $tValue;
            }
            if (is_string($key)) {
                $restrictedFilters[$key] = Arr::wrap($value);
            }
        }

        return $this->sanitizedRestrictedFilters = $restrictedFilters;
    }

    /**
     * @param string $field
     *
     * @return array<int, string>|null
     */
    public function getAvailableFiltersFor(string $field): array|null
    {
        $this->getRestrictedFilters();

        return Arr::get($this->sanitizedRestrictedFilters, $field);
    }

    /**
     *  list models relations.
     *
     * @return array
     */
    private function relations(): array
    {
        $methods = (new ReflectionClass(get_called_class()))->getMethods();

        return collect($methods)
            ->filter(
                fn ($method) => !empty($method->getReturnType()) &&
                    str_contains(
                        $method->getReturnType(),
                        'Illuminate\Database\Eloquent\Relations'
                    )
            )
            ->map(fn ($method) => $method->name)
            ->values()->all();
    }

    /**
     * @param Builder      $query
     * @param array|string $fields
     *
     * @return Builder
     */
    public function scopeFilterFields(Builder $query, array|string $fields): Builder
    {
        $this->filterFields = is_array($fields) ? $fields : array_slice(func_get_args(), 1);

        return $query;
    }

    /**
     * @param Builder      $query
     * @param array|string $restrictedFilters
     *
     * @return Builder
     */
    public function scopeRestrictedFilters(Builder $query, array|string $restrictedFilters): Builder
    {
        $this->restrictedFilters = Arr::wrap($restrictedFilters);

        return $query;
    }

    /**
     * @param Builder $query
     * @param array   $renamedFilterFields
     *
     * @return Builder
     */
    public function scopeRenamedFilterFields(Builder $query, array $renamedFilterFields): Builder
    {
        $this->renamedFilterFields = $renamedFilterFields;

        return $query;
    }
}
