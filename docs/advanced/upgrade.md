---
title: Upgrade Guide
prev:
  text: 'Null sort'
  link: '/advanced/sort/null-sort'
---

## Upgrade Guide

### Version 3
changed how `$filterFields` array works. it no longer renames fields, instead, it restricts filters that are accepted by the field as mentioned in the [Restrict filters](#restrict-filters) section.
to rename fields refer to [Rename fields](../advanced/rename.md). `sortFields` However, didnt change. 

### Version 2
changed filter function arguments. filter function no longer accepts filter methods, instead, filter function now accepts filter source as mentioned in [Custom Filters](#custom-filters) section.
to specify allowed filter methods use filterBy as mentioned in [Restrict Filters](../advanced/filter/restrict.md)