<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;


class ProductTag extends Model
{
    protected $fillable = ['product_id','tag_id'];


     // Relationship with Product
    public function product()
    {
        return $this->belongsTo(related: Product::class);
    }

}
