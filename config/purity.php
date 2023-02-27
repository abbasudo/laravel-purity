<?php

use Abbasudo\Purity\Filters\Strategies\BetweenFilter;
use Abbasudo\Purity\Filters\Strategies\ContainsFilter;
use Abbasudo\Purity\Filters\Strategies\EndsWithFilter;
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
use Abbasudo\Purity\Filters\Strategies\NullFilter;
use Abbasudo\Purity\Filters\Strategies\StartsWithFilter;

return [
    'filters' => [
        EqualFilter::class,
        InFilter::class,
        BetweenFilter::class,
        ContainsFilter::class,
        EndsWithFilter::class,
        GreaterThanFilter::class,
        GreaterOrEqualFilter::class,
        LessOrEqualFilter::class,
        LessThanFilter::class,
        NotContainsFilter::class,
        NotContainsSensitiveFilter::class,
        NotEqualFilter::class,
        NotInFilter::class,
        NullFilter::class,
        StartsWithFilter::class,
    ],

    'silent' => true,

    'custom_filters_location' => app_path('Filters'),
];
