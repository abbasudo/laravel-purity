---
title: Params Source
prev:
  text: 'Rename Fields'
  link: '/advanced/rename'
next:
  text: 'Allowed Fields'
  link: '/advanced/allowed'
---

### Changing Params Source
By Default, Purity gets params from filters index in query params,
overwrite this by passing params directly to filter or sort functions:
#### Filter
```php
// this will get filters from find query params
// `GET /api/users?find[name][$eq]=John`
Post::filter(request()->query('find'))->get();
```
```php
Post::filter($params)->get();

Post::filter([
            'title' => ['$eq' => 'good post']
        ])->get();
        
Post::filter([
            '$or' => [
                'title' => [
                    '$eq' => 'good post'
                ],
                'author' => [
                    'name' => [ '$eq' => 'John Doe' ],
                    'age' => [ '$gt' => 20 ],
                ],
            ],
        ])->get();
```
#### Sort
```php
Post::sort([
            'title',
            'id:desc'
        ])->get();
```