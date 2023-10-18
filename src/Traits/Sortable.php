<?php

namespace Abbasudo\Purity\Traits;

use Abbasudo\Purity\Exceptions\FieldNotSupported;
use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;
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
     * @param Builder $query
     *
     * @return Builder
     * @throws Exception
     */
    public function scopeSort(Builder $query): Builder
    {
        $fields = request('sort', []);

        if (!is_array($fields)) {
            $fields = [$fields];
        }

        foreach ($fields as $field) {
            $column = Str::of($field)->beforeLast(':');

            $this->validate($column);

            $column = $this->getSortField($column);

            if (Str::of($field)->endsWith(':desc')) {
                $query->orderByDesc($column);
            } else {
                $query->orderBy($column);
            }
        }

        return $query;
    }

    /**
     * @throws Exception
     */
    private function validate(string $field): void
    {
        $available = $this->availableSort();

        $this->safe(function () use ($field, $available) {
            if (!in_array($field, $available)) {
                throw FieldNotSupported::create($field, self::class, $available);
            }
        });
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
     * @return bool
     * @throws Exception
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
}
