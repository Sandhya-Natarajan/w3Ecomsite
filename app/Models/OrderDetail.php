<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Product;


class OrderDetail extends Model
{
    protected $fillable=['order_id','product_id','quantity','price'];


    // Relationship with Order
    public function order()
    {
        return $this->belongsTo(related: Order::class);
    }

    // Relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
