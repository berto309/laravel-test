<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
    
        
        $filePath = storage_path('app/public/products.json');

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File cannot found.'], 404);
        }

        $products = json_decode(file_get_contents($filePath), true);

        return response()->json(
            data: [
            'products' => $products,
            'message' => 'success',
        ]);
    }

    public function store(Request $request): JsonResponse
    {
    
        $data = [
            'product_name' => $request->product_name,
            'price' => $request->price,
            'stock' => $request->stock,
            'created_at' => now(),
            'total_value' => (int) $request->price * (int) $request->stock
        ];
        
       
        $filePath = storage_path('app/public/products.json');

        if(file_exists($filePath)) {

            $existingData = json_decode(file_get_contents($filePath), true);
        } else {

            $existingData =  [];
        }

        $existingData[] = $data;
        file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT));
       
        
        
        return response()->json(
            data: [
            'message' => 'product created!'
        ]);
    }
}
