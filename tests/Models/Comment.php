<?php

namespace Abbasudo\Purity\Tests\Models;

use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    use Filterable;
    use Sortable;

    protected $fillable = [
        'content',
    ];
}
