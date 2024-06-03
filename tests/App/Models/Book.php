<?php

namespace Abbasudo\Purity\Tests\App\Models;

use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use Filterable;
    use Sortable;

    protected $filterFields = [
        'name', // field
        'description', // field
    ];

    protected $fillable = [
        'name',
        'description',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
