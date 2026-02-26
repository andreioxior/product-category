<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Cache::remember('api_categories', now()->addHours(1), function () {
            return Category::where('is_active', true)
                ->orderBy('name')
                ->get();
        });

        return response()->json([
            'data' => $categories,
        ]);
    }

    public function show(Category $category): JsonResponse
    {
        $category->load(['products' => function ($query) {
            $query->where('is_active', true)->limit(20);
        }]);

        return response()->json([
            'data' => $category,
        ]);
    }
}
