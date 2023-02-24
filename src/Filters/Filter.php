<?php

namespace Abbasudo\Purity\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class Filter implements \Abbasudo\Purity\Contracts\Filter
{
    protected Builder $query;
    protected string $column;
    protected array $filters;

    public function __construct(Builder $query, string $column, array $filters)
    {
        $this->query   = $query;
        $this->column  = $column;
        $this->filters = $filters;
    }

    /**
     * @return string
     */
    public static function operator(): string
    {
        return static::$operator;
    }
}
