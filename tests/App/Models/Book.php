<?php

namespace Abbasudo\Purity\Tests\App\Models;

use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Book extends Model
{
    use Filterable;
    use Sortable;

    protected $filterFields = [
        'name', // field
        'description', // field
        'page_count', // field
    ];

    protected $fillable = [
        'name',
        'description',
        'page_count',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function posts(): MorphMany
    {
        return $this->morphMany(Post::class, 'postable');
    }
}
