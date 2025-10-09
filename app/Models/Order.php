<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_amount'
    ];

    // Relationship with OrderDetail
    public function products()
    {

        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }


    // Optional: relation to user if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $term)
    {
        if ($term) {
            $query->where('id', $term)
                ->orWhereHas('user', function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%");
                });
        }
        return $query;
    }

    /**
     * Scope for orders created today
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
