<?php

namespace Abbasudo\Purity\Traits;

use Illuminate\Support\Arr;

trait getColumns
{
    /**
     * @return array
     */
    private function getTableColumns(): array
    {
        // optimize by getting columns only once
        $this->columns = $this->columns ??
            $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());

        // allow using qualified column names
        $qualifiedColumns = collect($this->columns)
                ->map(fn ($column) => $this->qualifyColumn($column))
                ->toArray();

        return array_merge($this->columns, $qualifiedColumns);
    }

    private function realName(array $fields, string $field): string
    {
        return Arr::get($fields, $field, $field);
    }
}
