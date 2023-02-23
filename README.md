<p align="center">
  <img src="/art/purity-logo.png" alt="Social Card of Laravel Purity">
  <h2 align="center">Elegant way to filter and sort</h2>
</p>

<!-- ABOUT -->
Laravel Purity is an elegant and efficient filtering and sorting package for Laravel, designed to simplify complex data filtering and sorting logic. By simply adding `filter()` to your Eloquent query, you can add the ability for frontend users to apply filters.

features :
 - verios filter methods
 - filter by relation coulmns
 - custom filters
 - multi-column sort

Laravel Purity is not only developer-friendly but also front-end developer-friendly. Frontend developers can effortlessly use filtering and sorting of the APIs by using the popular [JavaScript qs](https://www.npmjs.com/package/qs) package.

the way that this package handles filters is inspired by strapi [filter](https://docs.strapi.io/dev-docs/api/rest/filters-locale-publication#filtering) and [sort](https://docs.strapi.io/dev-docs/api/rest/sort-pagination#sorting) functionality.

## Installation
install the package via composer by this command:
   ```sh
   composer require abbasudo/laravel-purity 
   ```
## Basic Usage
### Filters
### Sort
## Queries
### Filters
Queries can accept a filters parameter with the following syntax:

`GET /api/:pluralApiId?filters[field][operator]=value`

The following operators are available:
| Operator      | Description                                      |
| ------------- | ------------------------------------------------ |
| `$eq`         | Equal                                            |
| `$eqi`        | Equal (case-insensitive)                         |
| `$ne`         | Not equal                                        |
| `$lt`         | Less than                                        |
| `$lte`        | Less than or equal to                            |
| `$gt`         | Greater than                                     |
| `$gte`        | Greater than or equal to                         |
| `$in`         | Included in an array                             |
| `$notIn`      | Not included in an array                         |
| `$contains`   | Contains                                         |
| `$notContains`| Does not contain                                 |
| `$containsi`  | Contains (case-insensitive)                      |
| `$notContainsi`| Does not contain (case-insensitive)             |
| `$null`       | Is null                                          |
| `$notNull`    | Is not null                                      |
| `$between`    | Is between                                       |
| `$startsWith` | Starts with                                      |
| `$startsWithi`| Starts with (case-insensitive)                   |
| `$endsWith`   | Ends with                                        |
| `$endsWithi`  | Ends with (case-insensitive)                     |
| `$or`         | Joins the filters in an "or" expression          |
| `$and`        | Joins the filters in an "and" expression         |



## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
