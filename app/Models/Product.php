<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductTag;


class Product extends Model
{

    protected $fillable = [
        'name',
        'cat_id',
        'desc',
        'price',
        'stock'
    ];

    // Relationship with ProductTag
    public function productTag() {

        return $this->hasMany(ProductTag::class);
    }



    // Cascade delete Tags when Product is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            if ($product->productTag) {
                $product->productTag()->delete();
            }
        });
    }

}
