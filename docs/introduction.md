---
title: Introduction
---
<p align="center">
  <img :src="$withBase('/images/purity-logo.png')" alt="Social Card of Laravel Purity">
  <h1 align="center">Elegant way to filter and sort queries in Laravel</h1>
</p>

[![Tests](https://github.com/abbasudo/laravel-purity/actions/workflows/tests.yml/badge.svg)](https://github.com/abbasudo/laravel-purity/actions/workflows/tests.yml)
[![License](http://poser.pugx.org/abbasudo/laravel-purity/license)](https://github.com/abbasudo/laravel-purity)
[![Latest Unstable Version](http://poser.pugx.org/abbasudo/laravel-purity/v)](https://packagist.org/packages/abbasudo/laravel-purity)
[![PHP Version Require](http://poser.pugx.org/abbasudo/laravel-purity/require/php)](https://packagist.org/packages/abbasudo/laravel-purity)
[![StyleCI](https://github.styleci.io/repos/603931433/shield)](https://packagist.org/packages/abbasudo/laravel-purity)

Laravel Purity is an elegant and efficient filtering and sorting package for Laravel, designed to simplify complex data filtering and sorting logic for eloquent queries. By simply adding `filter()` to your Eloquent query, you can add the ability for frontend users to apply filters based on URL query string parameters like a breeze.

Features :
- Livewire support
- Rename and restrict fields
- Various filter methods
- Simple installation and usage
- Filter by relation columns
- Custom filters
- Multi-column sort

Laravel Purity is not only developer-friendly but also front-end developer-friendly. Frontend developers can effortlessly use filtering and sorting of the APIs by using the popular [JavaScript qs](https://www.npmjs.com/package/qs) package.

The way this package handles filters is inspired by strapi's [filter](https://docs.strapi.io/dev-docs/api/rest/filters-locale-publication#filtering) and [sort](https://docs.strapi.io/dev-docs/api/rest/sort-pagination#sorting) functionality.