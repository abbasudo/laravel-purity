---
title: Basic Usage
description: Learn how to use Purity's basic features.
prev:
  text: 'Installation'
  link: '/guide/installation'
next:
  text: 'Available Methods'
  link: '/js-examples/available-methods'
---
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

Now add `filter()` to your model eloquent query in the controller.

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

By default, it gives access to all filters available. here is the list of [available filters](../js-examples/available-methods.md). if you want to explicitly specify which filters to use in this call head to [restrict filters](#restrict-filters) section.

### Sort

#### Sort Basics
Add `Sortable` trait to your model to get sorts functionalities.

```php
use Abbasudo\Purity\Traits\Sortable;

class Post extends Model
{
    use Sortable;
    
    //
}
```

Now add `sort()` to your eloquent query in the controller.

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

Now sort can be applied as instructed in [apply sort](../js-examples/sort.md).