<?php

namespace Abbasudo\Purity\Traits;

use Abbasudo\Purity\Filters\FilterList;
use Abbasudo\Purity\Filters\Resolve;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use ReflectionClass;

/**
 * List of available filters, can be set on the model otherwise it will be read from config.
 *
 * @property array $filters
 *
 * List of available fields, if not declared will accept every thing.
 * @property array $filterFields
 */
trait Filterable
{
    use getColumns;

    /**
     * Apply filters to the query builder instance.
     *
     * @param Builder           $query
     * @param array|string|null $availableFilters
     *
     * @return Builder
     * @throws Exception
     *
     */
    public function scopeFilter(Builder $query, array|string|null $availableFilters = null): Builder
    {
        // if not passed it will get the available filters from config
        if (isset($availableFilters)) {
            // set all function input except first one (witch is the query)
            $this->setFilters(
                is_array($availableFilters) ? $availableFilters : array_slice(func_get_args(), 1)
            );
        }

        app()->singleton(FilterList::class, function () {
            return (new FilterList())->only($this->getFilters());
        });

        // Retrieve the filters from the request
        $filters = request('filters', []);

        // Apply each filter to the query builder instance
        foreach ($filters as $field => $value) {
            app(Resolve::class, ['model' => $this])->apply($query, $field, $value);
        }

        return $query;
    }

    /**
     * @param $filters
     *
     * @return Filterable
     */
    public function setFilters($filters): static
    {
        $this->filters = is_array($filters) ? $filters : func_get_args();

        return $this;
    }

    /**
     * @return array
     */
    private function getFilters(): array
    {
        return $this->filters ?? config('purity.filters');
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function getField(string $field): string
    {
        return $this->realName($this->availableFields(), $field);
    }

    /**
     * @return array
     */
    public function availableFields(): array
    {
        return $this->filterFields ?? array_merge($this->getTableColumns(), $this->relations());
    }

    /**
     *  list models relations
     *
     * @return array
     */
    private function relations(): array
    {
        $methods = (new ReflectionClass(get_called_class()))->getMethods();

        return collect($methods)
            ->filter(
                fn($method) => !empty($method->getReturnType()) &&
                    str_contains(
                        $method->getReturnType(),
                        'Illuminate\Database\Eloquent\Relations'
                    )
            )
            ->map(fn($method) => $method->name)
            ->values()->all();
    }
}
