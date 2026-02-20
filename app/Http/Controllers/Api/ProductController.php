<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'bike'])->where('is_active', true);

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%")
                    ->orWhere('type', 'ilike', "%{$search}%");
            });
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('sort')) {
            match ($request->sort) {
                'price_asc' => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                'newest' => $query->orderByDesc('created_at'),
                'name_asc' => $query->orderBy('name'),
                'name_desc' => $query->orderByDesc('name'),
                default => $query->orderBy('name'),
            };
        } else {
            $query->orderBy('name');
        }

        $perPage = min($request->get('per_page', 20), 100);
        $products = $query->paginate($perPage);

        return response()->json([
            'data' => ProductResource::collection($products)->resolve(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'first_page_url' => $products->url(1),
                'last_page_url' => $products->url($products->lastPage()),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load(['category', 'bike']);

        return response()->json([
            'data' => new ProductResource($product),
        ]);
    }

    public function featured(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 10), 50);

        $products = Product::with(['category', 'bike'])
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return response()->json([
            'data' => ProductResource::collection($products)->resolve(),
        ]);
    }
}
