<?php

namespace Abbasudo\Purity\Sorts\Strategies;

use Abbasudo\Purity\Sorts\SortAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HasManySort extends SortAbstract
{
    public function apply(): Builder
    {
        /** @var Model $relatedModel */
        $relatedModel = $this->model->{$this->relationName}()->getRelated();
        $foreignKeyKey = $this->model->{$this->relationName}()->getQualifiedForeignKeyName();
        $localKey = $this->model->{$this->relationName}()->getQualifiedParentKeyName();
        $relatedTable = $relatedModel->getTable();

         return $this->query->orderBy(
              $relatedModel::query()
            ->select("{$relatedTable}.{$this->column}")
            ->whereColumn($localKey, $foreignKeyKey)
            ->orderByRaw("{$relatedTable}.{$this->column} {$this->direction}")
            ->limit(1),
             $this->direction
        );
    }
}