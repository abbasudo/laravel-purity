---
title: Null Sort
prev:
  text: 'Custom Filters'
  link: '/advanced/filter/custom'
next:
  text: 'Upgrade Guide'
  link: '/advanced/upgrade'
---

### Sort null values last
When sorting a column that contains null values, it's typically preferred to have those values appear last, regardless of the sorting direction. You can enable this feature in the configuration as follows:
```php
// configs/purity.php

null_last => true;
```