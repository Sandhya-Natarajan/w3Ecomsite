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
            'name'=> $this->name,
            'cat_id'=> $this->cat_id,
            'tag_id'=> $this->productTag->pluck('tag_id'),
            'desc'=> $this->desc,
            'price'=> $this->price,
            'stock'=> $this->stock,

        ];
    }
}
