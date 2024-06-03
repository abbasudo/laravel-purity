<?php

namespace Abbasudo\Purity\Tests\App\Models;

use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    use HasFactory;
    use Filterable;
    use Sortable;

    protected $fillable = [
        'name',
    ];

    public function post(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function project(): HasOne
    {
        return $this->hasOne(Project::class);
    }
}
