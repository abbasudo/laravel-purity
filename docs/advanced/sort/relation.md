---
title: Relation
---

#### Sort by Relation
The following relationship types are supported.
- One to One
- One to Many
- Many to Many

Return type of the relationship mandatory as below in order to sort by relationships.

```php
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use Sortable;
    
    public function tags(): HasMany // This is mandatory
    {
        return $this->hasMany(Tag::class);
    }
}
```