<?php

namespace Abbasudo\Purity\Traits;

use Abbasudo\Purity\Filters\Resolve;
use Exception;
use Illuminate\Database\Eloquent\Builder;

/**
 * List of available filters, can be set on the model otherwise it will be read from config
 *
 * @property array $filters
 */
trait Filterable
{
    /**
     * Apply filters to the query builder instance
     *
     * @param  Builder  $query
     * @param  array|string|null  $availableFilters
     *
     * @return Builder
     * @throws Exception
     */
    public function scopeFilter(Builder $query, array|string|null $availableFilters = null): Builder
    {
        // if not passed it will get the available filters from config
        if (isset($availableFilters)) {
            // set all function input except first one (witch is the query)
            $this->setFilters(array_slice(func_get_args(), 1));
        }

        // Retrieve the filters from the request
        $filters = request('filters', []);

        // Apply each filter to the query builder instance
        foreach ($filters as $field => $value) {
            (new Resolve($this->getFilters()))->apply($query, $field, $value);
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
}
