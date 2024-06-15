---
title: Available Filters
description: Learn how to use available filters in Laravel Purity.
prev:
  text: 'Basic Usage'
  link: '/guide/basic-usage'
next:
  text: 'JS Example'
  link: '/js-examples/filter'
---

## Queries and javascript examples
This section is a guide for front-end developers who want to use an API that uses Laravel Purity.
### Available Filters
Queries can accept a filters parameter with the following syntax:

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
| `$notBetween`   | Is not between                           |
| `$startsWith`   | Starts with                              |
| `$startsWithc`  | Starts with (case-sensitive)             |
| `$endsWith`     | Ends with                                |
| `$endsWithc`    | Ends with (case-sensitive)               |
| `$or`           | Joins the filters in an "or" expression  |
| `$and`          | Joins the filters in an "and" expression |
