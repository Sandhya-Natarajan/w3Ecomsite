<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
use App\Models\Order;

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

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
