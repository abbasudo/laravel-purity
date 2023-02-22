<?php

namespace Abbasudo\LaravelPurity\Traits;

use Abbasudo\LaravelPurity\Filters\Resolve;
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
     * @param  array|null  $availableFilters
     *
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $availableFilters = null): Builder
    {
        if (isset($availableFilters)) {
            $this->setFilters($availableFilters);
        }

        // Retrieve the filters from the request
        $filters = request('filters', []);

        // Apply each filter to the query builder instance
        foreach ($filters as $field => $value) {
            (new Resolve($this->getFilters()))->applyFilter($query, $field, $value);
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
        return $this->filters ?? config('purity.available_filters');
    }
}
