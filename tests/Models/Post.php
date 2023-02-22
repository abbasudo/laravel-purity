<?php

namespace Abbasudo\LaravelPurity\Tests\Models;

use Abbasudo\LaravelPurity\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    use Filterable;

    protected $fillable = [
        'title',
    ];
}
