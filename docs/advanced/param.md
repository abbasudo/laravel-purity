---
title: Params Source
---

### Changing Params Source
By Default, purity gets params from filters index in query params, overwrite this by passing params directly to filter or sort functions:

```php
Post::filter($params)->get();

Post::filter([
            'title' => ['$eq' => 'good post']
        ])->get();

Post::sort([
            'title',
            'id:desc'
        ])->get();
```