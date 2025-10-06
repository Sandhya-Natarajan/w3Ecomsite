<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'total_amount' => $this->total_amount,
            'user_id' => $this->user_id,


            // Include related order details
            'order_details' => $this->whenLoaded('orderDetails', function () {
                return $this->orderDetails->map(function ($detail) {
                    return [
                        'product_id' => $detail->product_id,
                        'product_name' => $detail->product->name ?? null, // requires product() relation
                        'quantity' => $detail->quantity,
                        'price' => $detail->price,
                    ];
                });
            }),
        ];
    }
}
