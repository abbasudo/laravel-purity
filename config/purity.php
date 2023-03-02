<?php

use Abbasudo\Purity\Filters\Strategies\AndFilter;
use Abbasudo\Purity\Filters\Strategies\BetweenFilter;
use Abbasudo\Purity\Filters\Strategies\ContainsCaseSensitiveFilter;
use Abbasudo\Purity\Filters\Strategies\ContainsFilter;
use Abbasudo\Purity\Filters\Strategies\EndsWithCaseSensitiveFilter;
use Abbasudo\Purity\Filters\Strategies\EndsWithFilter;
use Abbasudo\Purity\Filters\Strategies\EqualCaseSensitiveFilter;
use Abbasudo\Purity\Filters\Strategies\EqualFilter;
use Abbasudo\Purity\Filters\Strategies\GreaterOrEqualFilter;
use Abbasudo\Purity\Filters\Strategies\GreaterThanFilter;
use Abbasudo\Purity\Filters\Strategies\InFilter;
use Abbasudo\Purity\Filters\Strategies\LessOrEqualFilter;
use Abbasudo\Purity\Filters\Strategies\LessThanFilter;
use Abbasudo\Purity\Filters\Strategies\NotContainsFilter;
use Abbasudo\Purity\Filters\Strategies\NotContainsSensitiveFilter;
use Abbasudo\Purity\Filters\Strategies\NotEqualFilter;
use Abbasudo\Purity\Filters\Strategies\NotInFilter;
use Abbasudo\Purity\Filters\Strategies\NotNullFilter;
use Abbasudo\Purity\Filters\Strategies\NullFilter;
use Abbasudo\Purity\Filters\Strategies\OrFilter;
use Abbasudo\Purity\Filters\Strategies\StartsWithCaseSensitiveFilter;
use Abbasudo\Purity\Filters\Strategies\StartsWithFilter;

return [
    'filters' => [
        EqualFilter::class,
        InFilter::class,
        BetweenFilter::class,
        ContainsFilter::class,
        EndsWithFilter::class,
        GreaterThanFilter::class,
        NotNullFilter::class,
        StartsWithFilter::class,
        GreaterOrEqualFilter::class,
        LessOrEqualFilter::class,
        LessThanFilter::class,
        NotContainsFilter::class,
        NotEqualFilter::class,
        NotInFilter::class,
        NullFilter::class,
        AndFilter::class,
        OrFilter::class,
        NotContainsSensitiveFilter::class,
        StartsWithCaseSensitiveFilter::class,
        EndsWithCaseSensitiveFilter::class,
        EqualCaseSensitiveFilter::class,
        ContainsCaseSensitiveFilter::class,
    ],

    'silent' => true,

    'custom_filters_location' => app_path('Filters'),
];
