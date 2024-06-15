---
title: Filtering
description: Learn how to filter data in JS using Laravel Purity.
prev:
  text: 'Available Methods'
  link: '/js-examples/available-methods'
next:
  text: 'Rename Fields'
  link: '/advanced/rename'
---

### Simple Filtering

::: tip
In javascript uses [qs](https://www.npmjs.com/package/qs) directly
to generate complex queries instead of creating them manually.
Examples in this documentation showcase how you can use `qs`.
:::

#### Eq Filter

Find users with 'John' as their first name

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

#### In Filter

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

#### Between Filter

Find users with age between 20 and 30

`GET /api/users?filters[age][$between][0]=20&filters[age][$between][1]=30`

```js
const qs = require('qs');
const query = qs.stringify({
  filters: {
    age: {
      $between: [20, 30],
    },
  },
}, {
  encodeValuesOnly: true, // prettify URL
});

await request(`/api/users?${query}`);
```

### Complex Filtering

Complex filtering is combining multiple filters using advanced methods such as combining `$and` & `$or`. This allows for
more flexibility to request exactly the data needed.

Find books with two possible dates and a specific author.

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

### Relation Filtering

Relation filtering is filtering on a relation's fields.

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

::: warning
Relation must be defined in the Laravel model.
Read more about relation filtering at [relations](../advanced/relation) in the advanced section.
:::

### Complex Relation Filtering

Complex relation filtering is combining multiple relation filters
using advanced methods such as combining `$and` & `$or`.
This allows for more flexibility to request exactly the data needed.

Find restaurants owned by a chef who belongs to a 5-star restaurant and has a specific cuisine

`GET /api/restaurants?filters[chef][restaurants][stars][$eq]=5&filters[chef][restaurants][cuisine][$eq]=Italian`

```js
const qs = require('qs');
const query = qs.stringify({
  filters: {
    chef: {
      restaurants: {
        stars: {
          $eq: 5,
        },
        cuisine: {
          $eq: 'Italian',
        },
      },
    },
  },
}, {
  encodeValuesOnly: true, // prettify URL
});

await request(`/api/restaurants?${query}`);
```

#### Laravel Example

Implement the same filter manually by passing an array of filters to the `filter()` method.

```php
$params = [
    'filters' => [
        'chef' => [
            'restaurants' => [
                'stars' => [
                    '$eq' => 5,
                ],
                'cuisine' => [
                    '$eq' => 'Italian',
                ],
            ],
        ],
    ],
];

$restaurants = Restaurant::filter($params)->get();
```

Read more about at [params source](../advanced/param) in the advanced section.
