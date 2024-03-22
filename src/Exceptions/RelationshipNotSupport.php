<?php

namespace Abbasudo\Purity\Exceptions;

use InvalidArgumentException;

class RelationshipNotSupport extends InvalidArgumentException
{
    private static $supportedRelationships = [
        'hasOne',
        'belongsTo',
        'hasMany',
        'belongsToMany'
    ];

    public static function create()
    {
        return new static(
            "Relationship not support, supported relationships " . implode(", ", self::$supportedRelationships)
        );
    }
}
