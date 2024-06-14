---
title: Filtering
prev:
  text: 'Available Methods'
  link: '/js-examples/available-methods'
next:
  text: 'Rename Fields'
  link: '/advanced/rename'
---

#### Simple Filtering

::: tip
In javascript use [qs](https://www.npmjs.com/package/qs) directly to generate complex queries instead of creating them manually. Examples in this documentation showcase how you can use `qs`.
:::

Find users having 'John' as their first name

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
#### Relation Filtering
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
