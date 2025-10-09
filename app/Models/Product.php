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

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {

            if (method_exists($query, 'whereAny')) {
                return $query->whereAny(['name', 'description'], 'like', "%{$search}%");
            }

            return $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        return $query;
    }

    public function scopeCategory($query, $categoryId)
    {
        if (!empty($categoryId)) {
            return $query->where('category_id', $categoryId);
        }

        return $query;
    }


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
