<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductTag;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{

    protected $fillable = [
        'name',
        'category_id',
        'description',
        'price',
        'stock'
    ];


    /**
     * Many-to-Many relationship with Tag
     */

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
