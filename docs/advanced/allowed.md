---
title: Allowed Fields
prev:
  text: 'Rename Fields'
  link: '/advanced/rename'
next:
  text: 'Livewire'
  link: '/advanced/livewire'
---

### Allowed Fields
By default, Purity allows every database column and all model relations (that have a defined return type) to be filtered.
```php
// App\Models\User

public function posts(): Illuminate\Database\Eloquent\Relations\HasMany // This is mandatory
{
    return $this->hasMany(Post::class);
}
```

you can overwrite the allowed columns as follows:

```php
// App\Models\User

protected $filterFields = [
  'email',
  'mobile',
  'posts', // relation
];
    
protected $sortFields = [
  'name',
  'mobile',
];
```
any field other than email, mobile, or posts will be rejected when filtering.
#### Overwrite Allowed Fields
to overwrite allowed fields in the controller add `filterFields` or `sortFields` before calling filter or sort method.
```php
Post::filterFields('title', 'created_at')->filter()->get();

Post::sortFields('created_at', 'updated_at')->sort()->get();
```
::: tip
filterFields and sortFields will overwrite fields defined in the model.
:::