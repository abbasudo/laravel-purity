<?php

namespace Abbasudo\Purity\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait CanSortNullable
{
    public function sortByNullLast(string $column, $direction, Builder $query): Builder
    {
       return match (DB::getDriverName()) {
            'pgsql' => $query->orderByRaw("$column $direction nulls last"),
            default => $query->orderByRaw("$column is null")
                             ->orderByRaw("$column $direction")
        };
    }
}