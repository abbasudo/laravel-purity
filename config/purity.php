<?php

use Abbasudo\Purity\Filters\Strategies\EqualFilter;
use Abbasudo\Purity\Filters\Strategies\InFilter;

return [
    'filters' => [
        EqualFilter::class,
        InFilter::class,
    ],

    'silent' => true,
];
