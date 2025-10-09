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
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->whenLoaded('category', $this->category->makeHidden(['created_at', 'updated_at'])),
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'tags' => $this->whenLoaded('tags', $this->tags->map->only(['id', 'name'])),
            "created_at" => $this->created_at->format("d-m-y"),
            "updated_at" => $this->updated_at->format("d-m-y")

        ];
    }
}
