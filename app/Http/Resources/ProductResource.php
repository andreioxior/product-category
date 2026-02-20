<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'price' => (float) $this->price,
            'sku' => $this->sku,
            'stock_quantity' => $this->stock_quantity,
            'is_active' => $this->is_active,
            'image' => $this->image,
            'best_image_url' => $this->bestImageUrl,
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                ];
            }),
            'bike' => $this->whenLoaded('bike', function () {
                return [
                    'id' => $this->bike->id,
                    'manufacturer' => $this->bike->manufacturer,
                    'model' => $this->bike->model,
                    'year' => $this->bike->year,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
