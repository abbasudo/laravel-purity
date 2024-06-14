<p align="center">
  <img src="https://github.com/abbasudo/laravel-purity/raw/master/art/purity-logo.png" alt="Social Card of Laravel Purity">
  <h1 align="center">Elegant way to filter and sort queries in Laravel</h1>
</p>

[![Tests](https://github.com/abbasudo/laravel-purity/actions/workflows/tests.yml/badge.svg)](https://github.com/abbasudo/laravel-purity/actions/workflows/tests.yml)
[![License](http://poser.pugx.org/abbasudo/laravel-purity/license)](https://github.com/abbasudo/laravel-purity)
[![Latest Unstable Version](http://poser.pugx.org/abbasudo/laravel-purity/v)](https://packagist.org/packages/abbasudo/laravel-purity)
[![PHP Version Require](http://poser.pugx.org/abbasudo/laravel-purity/require/php)](https://packagist.org/packages/abbasudo/laravel-purity)
[![StyleCI](https://github.styleci.io/repos/603931433/shield)](https://packagist.org/packages/abbasudo/laravel-purity)

Laravel Purity is an elegant and yet simple filtering and sorting package for Laravel,
designed to simplify complex data filtering and sorting logic for eloquent queries.
By simply adding `filter()` to your Eloquent query,
you can add the ability for frontend users to apply filters based on URL query string parameters like a breeze.

## How Does Purity Work?
Here is a basic usage example to clarify Purity's use case.

Add `filter()` to your query.
```php
$posts = Post::filter()->get();
```
That's it!
Now you can filter your posts by adding query string parameters to the URL.
```
GET /api/posts?filters[title][$contains]=Purity
```
read more at official [documentations](https://abbasudo.github.io/laravel-purity/)

## Documentation
https://abbasudo.github.io/laravel-purity/

## License

Laravel Purity is Licensed under The MIT License (MIT). Please see [License File](https://github.com/abbasudo/laravel-purity/blob/master/LICENSE) for more information.

## Security

If you've found a bug regarding security, please mail [amkhzomi@gmail.com](mailto:amkhzomi@gmail.com) instead of
using the issue tracker.
