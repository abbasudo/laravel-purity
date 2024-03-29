<?php

namespace Abbasudo\Purity\Sorts\Strategies;

use Abbasudo\Purity\Sorts\SortAbstract;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\select;

class BelongsToManySort extends SortAbstract
{
    public function apply(): \Illuminate\Database\Eloquent\Builder
    {
        /** @var Model $relatedModel */
        $parentTable = $this->model->getTable();
        $relatedModel = $this->model->{$this->relationName}()->getRelated();
        $relatedTable = $relatedModel->getTable();
        $pivotTableName = $this->model->{$this->relationName}()->getTable();
        $qualifiedRelatedPivotKeyName = $this->model->{$this->relationName}()->getQualifiedRelatedPivotKeyName();
        $qualifiedForeignPivotKeyName = $this->model->{$this->relationName}()->getQualifiedForeignPivotKeyName();
        $qualifiedParentKeyName = $this->model->{$this->relationName}()->getQualifiedParentKeyName();
        $qualifiedRelatedKeyName = $this->model->{$this->relationName}()->getQualifiedRelatedKeyName();

        return $this->query
            ->select("{$parentTable}.*")
            ->join($pivotTableName, $qualifiedParentKeyName, '=', $qualifiedForeignPivotKeyName)
            ->join($relatedTable, $qualifiedRelatedPivotKeyName, '=', $qualifiedRelatedKeyName)
            ->groupBy($qualifiedParentKeyName)
            ->when($this->direction === 'desc',
                function (Builder $query) use ($relatedTable) {
                    $query->orderByRaw("max({$relatedTable}.{$this->column}) {$this->direction}");
                },
                function (Builder $query) use ($relatedTable) {
                    $query->orderByRaw("min({$relatedTable}.{$this->column}) {$this->direction}");
                }
            );
    }
}