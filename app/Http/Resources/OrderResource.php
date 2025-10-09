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
            'user' => $this->whenLoaded('user', $this->user->only(['id', 'name'])),

            // products using map->only
            'products' => $this->whenLoaded(
                'products',
                $this->products->map(function ($product) {
                    return array_merge(
                        $product->only(['id', 'name']),
                        [
                            'price' => $product->pivot->price,
                            'quantity' => $product->pivot->quantity,
                            'subtotal' => $product->pivot->quantity * $product->pivot->price,
                        ]
                    );
                })
            ),
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),

        ];
    }
}
