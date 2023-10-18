<?php

namespace Abbasudo\Purity\Traits;

use Abbasudo\Purity\Filters\FilterList;
use Abbasudo\Purity\Filters\Resolve;
use Exception;
use Illuminate\Database\Eloquent\Builder;

/**
 * List of available filters, can be set on the model otherwise it will be read from config.
 *
 * @property array $filters
 */
trait Filterable
{
    /**
     * Apply filters to the query builder instance.
     *
     * @param Builder $query
     * @param array|null $params
     * @return Builder
     * @throws Exception
     */
    public function scopeFilter(Builder $query, array|null $params = null): Builder
    {
        app()->singleton(FilterList::class, function () {
            return (new FilterList())->only($this->getFilters());
        });

        if (!isset($params)) {
            // Retrieve the filters from the request
            $params = request('filters', []);
        }

        // Apply each filter to the query builder instance
        foreach ($params as $field => $value) {
            app(Resolve::class)->apply($query, $field, $value);
        }

        return $query;
    }

    /**
     * @return array
     */
    private function getFilters(): array
    {
        return $this->filters ?? config('purity.filters');
    }

    /**
     * @param Builder $query
     * @param array|string $filters
     *
     * @return Builder
     */
    public function scopeFilterBy(Builder $query, array|string $filters): Builder
    {
        $this->filters = is_array($filters) ? $filters : array_slice(func_get_args(), 1);

        return $query;
    }
}
