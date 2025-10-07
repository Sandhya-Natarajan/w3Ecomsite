<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::query();

        if (!empty($search = $request->get("search"))) {
            $query->where("name", "like", "%" . $search . "%")
                ->orWhere("desc", "like", "%" . $search . "%");
        }

        $categories = $query->latest()->paginate($request->get("per_page", 10));

        return response()->json([
            "data" => CategoryResource::collection($categories),
            "pagination" => [
                "current_page" => $categories->currentPage(),
                "last_page" => $categories->lastPage(),
                "per_page" => $categories->perPage(),
                "total" => $categories->total(),
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request, Category $category): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json([
            "data" => new CategoryResource($category),
            "message" => "Category Created Successfully",
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        return response()->json([
            "data" => new CategoryResource($category),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());

        return response()->json([
            "data" => new CategoryResource($category),
            "message" => "Category Updated Successfully",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            "message" => "Category Deleted Successfully",
        ], 200);
    }
}
