<?php

namespace Abbasudo\Purity\Tests\App\Models;

use Abbasudo\Purity\Tests\App\Factories\ProductFactory;
use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;
    use Filterable;
    use Sortable;

    protected static function newFactory()
    {
        return ProductFactory::new();
    }

    protected $fillable = [
        'name',
        'price',
        'rate',
        'description',
        'is_available',
    ];

    public function posts(): MorphMany
    {
        return $this->morphMany(Post::class, 'postable');
    }

    public function book(): HasOne
    {
        return $this->hasOne(Book::class);
    }
}
