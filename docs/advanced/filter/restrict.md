---
title: Restrict
prev:
  text: 'Silent Exceptions'
  link: '/advanced/silent'
next:
  text: 'Custom Filters'
  link: '/advanced/filter/custom'
---

### Restrict by Field
There are three available options for your convenience. They take priority respectively.
- **Option 1 : Define restricted filters inside `$filterFields` property, as shown below**
```php
$filterFields = [
  'title' => ['$eq'],  // title will be limited to the eq operator
  'title' => '$eq',    // works only for one restricted operator
  'title:$eq',         // same as above
  'title',             // this won't be restricted to any operator
];
```
The drawback here is that you have to define all the allowed fields, regardless of any restrictions fields.
- **Option 2 : Define them inside `$restrictedFilters` property**
```php
$restrictedFields = [
  'title' => ['$eq', '$eq'],  // title will be limited to the eq operator
  'title:$eq,$in'             // same as above
  'title'                     // this won't be restricted to any operator
];
```
- **Option 3 : Finally, you can set it on the Eloquent builder, which takes the highest priority (overwrite all the above options)**
```php
Post::restrictedFilters(['title' => ['$eq']])->filter()->get();

```
**Note**
>All field-restricted filter operations are respected to filters defined in $filter ([here](#restrict-filters)). This means you are not allowed to restrict a field operation that is not permitted in restricted fields.
```php
$filters = ['$eq'];
$restrictedFilters = ['title' => ['$eqc']] // This won't work
```

### Restrict Filters

purity validates allowed filters in the following order of priority:
- Filters specified in the `filters` configuration in the `configs/purity.php` file.

```php
// configs/purity.php
'filters' => [
  EqualFilter::class,
  InFilter::class,
],
```

- Filters declared in the `$filters` variable in the model.

note that, $filters will overwrite configs filters.

```php
// App\Models\Post

private array $filters = [
  '$eq',
  '$in',
];
    
// or
    
private array $filters = [
  EqualFilter::class,
  InFilter::class,
];
```

- Filters passed as an array to the `filterBy()` function.

note that, `filterBy` will overwrite all other defined filters.

```php
Post::filterBy('$eq', '$in')->filter()->get();
// or
Post::filterBy(EqualFilter::class, InFilter::class)->filter()->get();
```