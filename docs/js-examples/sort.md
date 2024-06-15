---
title: Sorting
description: Learn how to apply sorting to your API requests using Laravel Purity.
prev:
  text: 'Available Methods'
  link: '/js-examples/available-methods'
next:
  text: 'Rename Fields'
  link: '/advanced/rename'
---

### Apply Sort

#### Apply Basic Sorting
Queries can accept a sort parameter that allows sorting on one or multiple fields with the following syntax's:

`GET /api/:pluralApiId?sort=value` to sort on 1 field

`GET /api/:pluralApiId?sort[0]=value1&sort[1]=value2` to sort on multiple fields (e.g. on 2 fields)

The sorting order can be defined as:
- `:asc` for ascending order (default order, can be omitted)
- `:desc` for descending order.

*Usage Examples*

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
#### Apply Sort by Relationships

All the usages of basic sorting are applicable. Use dot(.) notation to apply relationship in the following format.

`?sort=[relationship name].[relationship column]:[sort direction]`

*Usage Examples*

The query below sorts posts by their tag name in ascending order (default sort direction).
Direction is not mandatory when sorted by ascending order.

`GET /api/posts?sort=tags.name:asc`

```js
const qs = require('qs');
const query = qs.stringify({
  sort: ['tags.name:asc'],
}, {
  encodeValuesOnly: true, // prettify URL
});

await request(`/api/posts?${query}`);
```

::: tip
Sorting by nested relationships is not supported by the package as of now.
:::

