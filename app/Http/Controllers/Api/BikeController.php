<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bike;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BikeController extends Controller
{
    public function manufacturers(): JsonResponse
    {
        $manufacturers = Bike::distinct()
            ->orderBy('manufacturer')
            ->pluck('manufacturer');

        return response()->json([
            'data' => $manufacturers,
        ]);
    }

    public function models(Request $request): JsonResponse
    {
        $request->validate([
            'manufacturer' => 'required|string',
        ]);

        $models = Bike::where('manufacturer', $request->manufacturer)
            ->distinct()
            ->orderBy('model')
            ->pluck('model');

        return response()->json([
            'data' => $models,
        ]);
    }

    public function years(Request $request): JsonResponse
    {
        $request->validate([
            'manufacturer' => 'required|string',
            'model' => 'required|string',
        ]);

        $years = Bike::where('manufacturer', $request->manufacturer)
            ->where('model', $request->model)
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return response()->json([
            'data' => $years,
        ]);
    }

    public function show(Request $request): JsonResponse
    {
        $request->validate([
            'manufacturer' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
        ]);

        $bike = Bike::where('manufacturer', $request->manufacturer)
            ->where('model', $request->model)
            ->where('year', $request->year)
            ->first();

        if (! $bike) {
            return response()->json([
                'message' => 'Bike not found',
            ], 404);
        }

        return response()->json([
            'data' => $bike,
        ]);
    }
}
