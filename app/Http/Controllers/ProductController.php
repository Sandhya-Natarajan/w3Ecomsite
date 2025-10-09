<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Tag;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {

        $products = Product::with(['category', 'tags'])
            ->search($request->get('search'))
            ->category($request->get('category_id'))
            ->latest()
            ->paginate($request->get('per_page', 10));

        return response()->json([
            "data" => ProductResource::collection($products),
            "pagination" => [
                "current_page" => $products->currentPage(),
                "last_page" => $products->lastPage(),
                "per_page" => $products->perPage(),
                "total" => $products->total(),
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request): JsonResponse
    {

        $product = Product::create($request->validated());

        if ($request->filled('tag_id')) {
            $product->tags()->sync($request->tag_id);
        }

        $product->load(['category', 'tags']);

        return response()->json([
            'data' => new ProductResource($product->load('tags')),
            'message' => "Product Created Successfully",
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        $product->load(['category', 'tags']);

        return response()->json([
            'data' => new ProductResource($product),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        if ($request->has('tag_id')) {
            $product->tags()->sync($request->tag_id);
        }

        $product->load(['category', 'tags']);

        return response()->json([
            "data" => new ProductResource($product),
            "message" => "Product Updated Successfully",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            "message" => "Product Deleted Successfully",
        ], 200);
    }
}
