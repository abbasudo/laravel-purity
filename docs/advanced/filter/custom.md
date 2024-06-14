---
title: Custom Filters
---
### Custom Filters
Create a custom filter class by this command:

```sh
php artisan make:filter EqualFilter
```

this will generate a filter class in `Filters` directory. By default, all classes defined in `Filters` directory are loaded into the package. you can change scan folder location in purity config file.

```php
// configs/purity.php

'custom_filters_location' => app_path('Filters'),
```