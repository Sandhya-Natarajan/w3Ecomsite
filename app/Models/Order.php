<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;
use App\Models\Product;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_amount'
    ];

    // Relationship with OrderDetail
    public function OrderDetails() {

        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }


    // Optional: relation to user if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
