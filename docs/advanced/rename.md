---
title: Rename Fields
---
#### Rename Filter Fields
To rename filter fields simply add a value to fields defined in `$renamedFilterFields`.
```php
// App\Models\User

// ?filter[phone][$eq]=0000000000

protected $renamedFilterFields = [
  'mobile' => 'phone', // Actual database column is mobile
  'posts'  => 'writing', // actual relation is posts
];

```
The client should send phone in order to filter by mobile column in database.


#### Rename Sort Fields
To rename sort fields simply add a value to defined in `$sortFields`
```php
// App\Models\User

// ?sort=phone
protected $sortFields = [
  'name',
  'mobile' => 'phone', // Actual database column is mobile
];
```
The client should send phone in order to sort by mobile column in database.
#### Overwrite Renamed Fields
To overwrite renamed fields in the controller you pass renamed fields to `rebamedFilterFields` and `sortFields`.
```php
Post::renamedFilterFields(['created_at' => 'published_date'])->filter()->get();

Post::sortFields([
    'created_at' => 'published_date',
    'updated_at'
  ])->sort()->get();
```
::: tip
SortFields will overwrite fields defined in the model.
:::
