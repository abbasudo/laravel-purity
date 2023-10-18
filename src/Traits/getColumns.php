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
}