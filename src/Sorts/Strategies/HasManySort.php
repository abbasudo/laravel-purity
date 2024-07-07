<?php

namespace Abbasudo\Purity\Sorts\Strategies;

use Abbasudo\Purity\Sorts\SortAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HasManySort extends SortAbstract
{
    public function apply(): Builder
    {
        /** @var Model $relatedModel */
        $relatedModel = $this->model->{$this->relationName}()->getRelated();
        $foreignKeyKey = $this->model->{$this->relationName}()->getQualifiedForeignKeyName();
        $localKey = $this->model->{$this->relationName}()->getQualifiedOwnerKeyName();
        $relatedTable = $relatedModel->getTable();
        $alias = $relatedTable . '_' . 'virtual_sort';

        return $this->query->orderBy(
            $relatedModel::query()
            ->from("{$relatedTable} as {$alias}")
            ->select("{$alias}.{$this->column}")
            ->whereColumn($alias. '.' .$localKey, $foreignKeyKey)
            ->orderByRaw("{$alias}.{$this->column} {$this->direction}")
            ->limit(1),
            $this->direction
        );
    }
}
