---
title: Home
layout: home
---

<p align="center">
  <img src="https://github.com/abbasudo/laravel-purity/raw/master/art/purity-logo.png" alt="Social Card of Laravel Purity">
  <h2 align="center">Elegant way to filter and sort queries in Laravel</h2>
</p>

[![Tests](https://github.com/abbasudo/laravel-purity/actions/workflows/tests.yml/badge.svg)](https://github.com/abbasudo/laravel-purity/actions/workflows/tests.yml)
[![License](http://poser.pugx.org/abbasudo/laravel-purity/license)](https://packagist.org/packages/abbasudo/laravel-purity)
[![Latest Unstable Version](http://poser.pugx.org/abbasudo/laravel-purity/v)](https://packagist.org/packages/abbasudo/laravel-purity)
[![PHP Version Require](http://poser.pugx.org/abbasudo/laravel-purity/require/php)](https://packagist.org/packages/abbasudo/laravel-purity)

> **Note**
> if you are front-end developer and what to make queries in an API that uses this package head to [queries](#queries-and-javascript-examples) section


Laravel Purity is an elegant and efficient filtering and sorting package for Laravel, designed to simplify complex data filtering and sorting logic. By simply adding `filter()` to your Eloquent query, you can add the ability for frontend users to apply filters.

Features :
- Various filter methods
- Filter by relation columns
- Custom filters
- Multi-column sort

Laravel Purity is not only developer-friendly but also front-end developer-friendly. Frontend developers can effortlessly use filtering and sorting of the APIs by using the popular [JavaScript qs](https://www.npmjs.com/package/qs) package.

The way this package handles filters is inspired by strapi's [filter](https://docs.strapi.io/dev-docs/api/rest/filters-locale-publication#filtering) and [sort](https://docs.strapi.io/dev-docs/api/rest/sort-pagination#sorting) functionality.

## Installation
Install the package via composer by this command:
```sh
composer require abbasudo/laravel-purity 
```
Get configs (`configs/purity.php`) file to customize package's behavior by this command:
```sh
php artisan vendor:publish --tag=purity 
```
## Basic Usage
### Filters
Add `Filterable` trait to your model to get filters functionalities.

```php
use Abbasudo\Purity\Traits\Filterable;

class Post extends Model
{
    use Filterable;
    
    //
}
```

now add `filter()` to your model query in the controller.

```php
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return Post::filter()->get();
    }
}
```

By default, it gives access to all filters available. here is the list of [available filters](#available-filters). if you want to explicitly specify which filters to use in this call head to [restrict filters](#restrict-filters) section.
### Sort
Add `Sortable` trait to your model to get sorts functionalities.

```php
use Abbasudo\Purity\Traits\Sortable;

class Post extends Model
{
    use Sortable;
    
    //
}
```

now add `sort()` to your model query in the controller.

```php
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return Post::sort()->get();
    }
}
```

Now sort can be applied as instructed in [sort usage](#usage-examples).
## Advanced Usage
### Restrict Filters

The system validates allowed filters in the following order of priority:
- Filters passed as an array to the `filter()` function.

```php
Post::filter('$eq', '$in')->get();
// or
Post::filter(EqualFilter::class, InFilter::class)->get();
```

- Filters declared in the `$filters` variable in the model.

> **Note**
> applied only if no parameters passed to `filter()` function.

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

- Filters specified in the `filters` configuration in the `configs/purity.php` file.

> **Note**
> applied only if above parameters are not set.
 
```php
// configs/purity.php
'filters' => [
  EqualFilter::class,
  InFilter::class,
],
```

### Custom Filters
Create custom filter class by this command:

```sh
php artisan make:filter EqualFilter
```

this will generate a filter class in `Filters` directory. by default all classes defined in `Filters` directory are loaded into the package. you can change scan folder location in purity config file.

```php
// configs/purity.php

'custom_filters_location' => app_path('Filters'),
```

### Silent Exceptions
By default, the package silences it own exceptions (not sql exceptions). to change that behavior change `silent` index to `false` in config file.

```php
// configs/purity.php

'silent' => false,
```

## Queries and javascript examples
This section is a guide for front-end developers who want to use an API that uses this package.
### Available Filters
Queries can accept a filters' parameter with the following syntax:

`GET /api/posts?filters[field][operator]=value`

**By Default** the following operators are available:

| Operator        | Description                              |
|-----------------|------------------------------------------|
| `$eq`           | Equal                                    |
| `$eqc`          | Equal (case-sensitive)                   |
| `$ne`           | Not equal                                |
| `$lt`           | Less than                                |
| `$lte`          | Less than or equal to                    |
| `$gt`           | Greater than                             |
| `$gte`          | Greater than or equal to                 |
| `$in`           | Included in an array                     |
| `$notIn`        | Not included in an array                 |
| `$contains`     | Contains                                 |
| `$notContains`  | Does not contain                         |
| `$containsc`    | Contains (case-sensitive)                |
| `$notContainsc` | Does not contain (case-sensitive)        |
| `$null`         | Is null                                  |
| `$notNull`      | Is not null                              |
| `$between`      | Is between                               |
| `$startsWith`   | Starts with                              |
| `$startsWithc`  | Starts with (case-sensitive)             |
| `$endsWith`     | Ends with                                |
| `$endsWithc`    | Ends with (case-sensitive)               |
| `$or`           | Joins the filters in an "or" expression  |
| `$and`          | Joins the filters in an "and" expression |

#### Simple Filtering

> **Tip**
>   in javascript use [qs](https://www.npmjs.com/package/qs) directly to generate complex queries instead of creating them manually. Examples in this documentation showcase how you can use `qs`.

Find users having 'John' as first name

`GET /api/users?filters[name][$eq]=John`
  ```js
  const qs = require('qs');
const query = qs.stringify({
  filters: {
    username: {
      $eq: 'John',
    },
  },
}, {
  encodeValuesOnly: true, // prettify URL
});

await request(`/api/users?${query}`);
  ```
Find multiple restaurants with ids 3, 6, 8

`GET /api/restaurants?filters[id][$in][0]=3&filters[id][$in][1]=6&filters[id][$in][2]=8`
  ```js
  const qs = require('qs');
const query = qs.stringify({
  filters: {
    id: {
      $in: [3, 6, 8],
    },
  },
}, {
  encodeValuesOnly: true, // prettify URL
});

await request(`/api/restaurants?${query}`);
  ```
#### Complex Filtering
Complex filtering is combining multiple filters using advanced methods such as combining `$and` & `$or`. This allows for more flexibility to request exactly the data needed.

Find books with 2 possible dates and a specific author.

`GET /api/books?filters[$or][0][date][$eq]=2020-01-01&filters[$or][1][date][$eq]=2020-01-02&filters[author][name][$eq]=Kai%20doe`
```js
const qs = require('qs');
const query = qs.stringify({
  filters: {
    $or: [
      {
        date: {
          $eq: '2020-01-01',
        },
      },
      {
        date: {
          $eq: '2020-01-02',
        },
      },
    ],
    author: {
      name: {
        $eq: 'Kai doe',
      },
    },
  },
}, {
  encodeValuesOnly: true, // prettify URL
});

await request(`/api/books?${query}`);
```
#### Deep Filtering
Deep filtering is filtering on a relation's fields.

Find restaurants owned by a chef who belongs to a 5-star restaurant

`GET /api/restaurants?filters[chef][restaurants][stars][$eq]=5`
```js
const qs = require('qs');
const query = qs.stringify({
  filters: {
    chef: {
      restaurants: {
        stars: {
          $eq: 5,
        },
      },
    },
  },
}, {
  encodeValuesOnly: true, // prettify URL
});

await request(`/api/restaurants?${query}`);
```
### Apply Sort
Queries can accept a sort parameter that allows sorting on one or multiple fields with the following syntax's:

`GET /api/:pluralApiId?sort=value` to sort on 1 field

`GET /api/:pluralApiId?sort[0]=value1&sort[1]=value2` to sort on multiple fields (e.g. on 2 fields)

The sorting order can be defined with:
- `:asc` for ascending order (default order, can be omitted)
- `:desc` for descending order.

#### Usage Examples

Sort using 2 fields

`GET /api/articles?sort[0]=title&sort[1]=slug`

```js
const qs = require('qs');
const query = qs.stringify({
  sort: ['title', 'slug'],
}, {
  encodeValuesOnly: true, // prettify URL
});

await request(`/api/articles?${query}`);
```



Sort using 2 fields and set the order

`GET /api/articles?sort[0]=title%3Aasc&sort[1]=slug%3Adesc`

```js
const qs = require('qs');
const query = qs.stringify({
  sort: ['title:asc', 'slug:desc'],
}, {
  encodeValuesOnly: true, // prettify URL
});

await request(`/api/articles?${query}`);
```

## License

The MIT License (MIT). Please see [License File](https://github.com/abbasudo/laravel-purity/blob/master/LICENSE) for more information.
