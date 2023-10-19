<?php

namespace Abbasudo\Purity\Traits;

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

        return $this->columns;
    }

    private function realName(array $fields, string $field): string
    {
        $real = array_search($field, $fields, true);

        return is_int($real) ? $field : $real;
    }
}
