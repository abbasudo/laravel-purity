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
     *
     * @return Builder
     */
    public function scopeSort(Builder $query): Builder
    {
        $fields = request('sort', []);

        if (!is_array($fields)) {
            $fields = [$fields];
        }

        foreach ($fields as $field) {
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
