<?php

namespace Abbasudo\Purity\Traits;

use Abbasudo\Purity\Exceptions\FieldNotSupported;
use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * List of available fields, if not declared will accept every thing.
 *
 * @property array $sortFields
 */
trait Sortable
{
    use getColumns;

    /**
     * Apply sorts to the query builder instance.
     *
     * @param Builder    $query
     * @param array|null $params
     *
     * @throws Exception
     *
     * @return Builder
     */
    public function scopeSort(Builder $query, array|null $params = null): Builder
    {
        if (!isset($params)) {
            $params = request()->query('sort', []);
        }

        if (!is_array($params)) {
            $params = [$params];
        }

        foreach ($params as $field) {
            $column = Str::of($field)->beforeLast(':');

            if (!$this->validate($column)) {
                continue;
            }

            $column = $this->getSortField($column);
            $this->applySort($column, $field, $query);
        }

        return $query;
    }

    /**
     * @throws Exception
     */
    private function validate(string $field): bool
    {
        $available = $this->availableSort();

        return $this->safe(function () use ($field, $available) {
            if (!in_array($field, $available)) {
                throw FieldNotSupported::create($field, self::class, $available);
            }
        });
    }

    /**
     * @param string  $column
     * @param string  $field
     * @param Builder $query
     *
     * @return void
     */
    public function applySort(string $column, string $field, Builder $query): void
    {
        $direction = Str::of($field)->lower()->endsWith(':desc') ? 'desc' : 'asc';

        if (config('purity.null_last', false)) {
            $this->sortByNullLast($column, $direction, $query);
        } else {
            $query->orderByRaw("$column $direction");
        }
    }

    /**
     * @param string  $column
     * @param         $direction
     * @param Builder $query
     *
     * @return Builder
     */
    public function sortByNullLast(string $column, $direction, Builder $query): Builder
    {
        return match (DB::getDriverName()) {
            'pgsql' => $query->orderByRaw("$column $direction nulls last"),
            default => $query->orderByRaw("$column is null")
                             ->orderByRaw("$column $direction")
        };
    }

    /**
     * @return array
     */
    private function availableSort(): array
    {
        return $this->sortFields ?? $this->getTableColumns();
    }

    /**
     * run functions with or without exception.
     *
     * @param Closure $closure
     *
     * @throws Exception
     *
     * @return bool
     */
    private function safe(Closure $closure): bool
    {
        try {
            $closure();

            return true;
        } catch (Exception $exception) {
            if (config('purity.silent')) {
                return false;
            } else {
                throw $exception;
            }
        }
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function getSortField(string $field): string
    {
        return $this->realName($this->availableSort(), $field);
    }

    /**
     * @param Builder      $query
     * @param array|string $fields
     *
     * @return Builder
     */
    public function scopeSortFields(Builder $query, array|string $fields): Builder
    {
        $this->sortFields = is_array($fields) ? $fields : array_slice(func_get_args(), 1);

        return $query;
    }
}
