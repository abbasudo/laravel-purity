<?php

namespace Abbasudo\Purity\Tests\Models;

use Abbasudo\Purity\Tests\Filters\CustomFilterResolver;
use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;
    use Filterable;
    use Sortable;

    protected function getFilterResolver(): string
    {
        return CustomFilterResolver::class;
    }

    protected $fillable = [
        'name',
    ];

    public function comments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class);
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
