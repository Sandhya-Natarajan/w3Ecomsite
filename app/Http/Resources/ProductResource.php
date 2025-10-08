<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Product id' => $this->id,
            'name' => $this->name,
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name
                ];
            }),
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'tag' => $this->whenLoaded('tags', function () {
                return $this->tags->map(function ($tag) {
                    return [
                        'id'   => $tag->id,
                        'name' => $tag->name,
                    ];
                });
            }),
            "created_at" => $this->created_at->format("d-m-y"),
            "updated_at" => $this->updated_at->format("d-m-y")

        ];
    }
}
