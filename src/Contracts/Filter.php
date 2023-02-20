<?php

namespace Abbasudo\LaravelPurity\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    public function apply(Builder $query, string $column, array $filters);
}
