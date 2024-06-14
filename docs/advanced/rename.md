---
title: Rename Fields
prev:
  text: 'Available Methods'
  link: '/js-examples/available-methods'
next:
  text: 'Relation Fields'
  link: '/advanced/relation'
---
#### Rename Filter Fields
To rename filter fields, you can add a value to fields defined in `$renamedFilterFields`. This is useful when you want to use a different name for a field in the client-side, while keeping the actual database column name intact.

```php
// App\Models\User

// Example URL: ?filter[phone][$eq]=0000000000

// The $renamedFilterFields property is used to map the client-side field names to the actual database column names.
protected $renamedFilterFields = [
  'mobile' => 'phone', // The actual database column is 'mobile', but the client should use 'phone'.
  'posts'  => 'writing', // The actual relation is 'posts', but the client should use 'writing'.
];
```
In this case, the client should send `phone` to filter by the mobile column in the database.

#### Rename Sort Fields

To rename sort fields, you can add a value to the defined key in `$sortFields`.
This is similar to renaming filter fields, but applies to sorting operations.
```php
// App\Models\User

// Example URL: ?sort=phone

// The $sortFields property is used to map the client-side field names to the actual database column names for sorting.
protected $sortFields = [
  'name',
  'mobile' => 'phone', // The actual database column is 'mobile', but the client should use 'phone' for sorting.
];
```
In this case, the client should send `phone` to sort by the mobile column in the database.
#### Overwrite Renamed Fields
Overwrite Renamed Fields
To overwrite renamed fields in the controller; you can pass renamed fields to `rebamedFilterFields` and `sortFields`.

```php
// Overwriting the renamed filter fields in the controller.
Post::renamedFilterFields(['created_at' => 'published_date'])->filter()->get();

// Overwriting the sort fields in the controller.
Post::sortFields([
    'created_at' => 'published_date',
    'updated_at'
  ])->sort()->get();
```
::: tip
Note that `sortFields` and `renamedFilterFields` will overwrite fields defined in the model. 
:::
