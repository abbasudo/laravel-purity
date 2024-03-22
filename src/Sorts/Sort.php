<?php

namespace Abbasudo\Purity\Sorts;

use Abbasudo\Purity\Exceptions\FieldNotSupported;
use Abbasudo\Purity\Helpers;
use Abbasudo\Purity\Sorts\Strategies\BelongsToSort;
use Abbasudo\Purity\Sorts\Strategies\DefaultSort;
use Abbasudo\Purity\Sorts\Strategies\HasManySort;
use Abbasudo\Purity\Sorts\Strategies\HasOneSort;
use Abbasudo\Purity\Sorts\Strategies\NullSort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Sort
{
    protected string $direction;

    protected ?Model $relation = null;
    protected string $relationName;

    public function __construct(
        protected string $column,
        protected string $field,
        protected Builder $query,
        protected Model $model,
    ) {
        // don't change the order
        $this->setRelationName();
        $this->setRelation();
        $this->setDirection();
        $this->setField();
    }

    public function __invoke(): Builder
    {
        if ($this->relation) {
            return $this->sortByRelation();
        }

        return $this->sortByColumn();
    }

    public function sortByColumn(): Builder
    {
        if (config('purity.null_last', false)) {
            return (new NullSort($this->column, $this->direction, $this->query))->apply();
        }
        return (new DefaultSort($this->column, $this->direction, $this->query))->apply();
    }

    public function sortByRelation(): Builder
    {
        $method = new \ReflectionMethod($this->model, $this->relationName);

        $type = $method->getReturnType()?->getName();

        if (config('purity.null_last')){
            $this->query->orderByRaw("{$this->field} is null");
        }

        return match ($type) {
            BelongsTo::class => (new BelongsToSort($this->field, $this->direction, $this->query, $this->model, $this->relationName))->apply(),
            HasOne::class => (new HasOneSort($this->field, $this->direction, $this->query, $this->model, $this->relationName))->apply(),
            HasMany::class => (new HasManySort($this->field, $this->direction, $this->query, $this->model, $this->relationName))->apply(),
        };
    }

    private function setRelationName(): void
    {
        if ($this->checkFieldHasRelationship()) {
            $this->relationName = Str::before($this->field, '.');
        }
    }

    private function setRelation(): void
    {
        if ($this->checkFieldHasRelationship()) {
            $relationName = Str::before($this->field, '.');
            $this->relation = $this->model->{$relationName}()->getRelated();
        }
    }

    private function setDirection(): void
    {
        $this->direction = Str::of($this->field)->lower()->endsWith(':desc') ? 'desc' : 'asc';
    }

    private function setField(): void
    {
        if ($this->checkFieldHasRelationship()) {
            $validFields = Helpers::getAvailableSortColumns($this->relation);
            $field = Str::between($this->field, '.', ':');

            throw_unless(in_array($field, $validFields), FieldNotSupported::create($field, class_basename($this->model), $validFields));

            $this->field = $this->realName($validFields, $field);
        }
    }

    private function realName(array $fields, string $field): string
    {
        $real = array_search($field, $fields, true);

        return is_int($real) ? $field : $real;
    }


    private function checkFieldHasRelationship(): bool
    {
        return Str::contains($this->field, '.');
    }
}