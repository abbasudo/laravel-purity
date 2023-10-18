<?php

namespace Abbasudo\Purity\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait Sortable
{
    /**
     * Apply sorts to the query builder instance.
     *
     * @param Builder $query
     * @param array|null $params
     * @return Builder
     */
    public function scopeSort(Builder $query, array|null $params = null): Builder
    {
        if (!isset($params)) {
            $params = request('sort', []);
        }

        if (!is_array($params)) {
            $params = [$params];
        }

        foreach ($params as $field) {
            $column = Str::of($field)->beforeLast(':');

            if (Str::of($field)->endsWith(':desc')) {
                $query->orderByDesc($column);
            } else {
                $query->orderBy($column);
            }
        }

        return $query;
    }
}
