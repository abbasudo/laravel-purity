---
title: Rename Fields
prev:
  text: 'Rename Fields'
  link: '/advanced/rename'
next:
  text: 'Allowed Fields'
  link: '/advanced/allowed'
---

Purity auto-detects relations in the model and allows you to filter and sort by them.

#### Filter by Relation

firstly, define the relation in the model

```php
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use Filterable;
    
    public function tags(): HasMany // This is mandatory
    {
        return $this->hasMany(Tag::class);
    }
}
```

Edit `$filterFields` property in the related model. By default, Purity allows all fields to be filtered.

```php
class Tags extends Model
{
    use Filterable;
    
    protected $filterFields = [
        'title',
    ];
}
```

Apply relation filtering examples at [examples page](../js-examples/filter.md#relation-filtering)

#### Sort by Relation

Currently, the following relationship types are supported.

- One to One
- One to Many
- Many to Many

Return type of the relations mandatory as below in order to sort by relationships.

```php
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use Sortable;
    
    public function tags(): HasMany // This is mandatory
    {
        return $this->hasMany(Tag::class);
    }
}
```

Apply relation filtering examples at [examples page](../js-examples/sort.md#apply-sort-by-relationships)
