<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Tag;
use App\Http\Resources\TagResource;
use App\Http\Resources\TagCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\JsonResponse;



class TagController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Tag::query();

        if (!empty($search = $request->get("search"))) {
            $query->where("name", "like", "%" . $search . "%");
        }

        $tags = $query->latest()->paginate($request->get("per_page", 10));

        return response()->json([
            "data" => TagResource::collection($tags),
            "pagination" => [
                "current_page" => $tags->currentPage(),
                "last_page" => $tags->lastPage(),
                "per_page" => $tags->perPage(),
                "total" => $tags->total(),
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTagRequest $request, Tag $tag): JsonResponse
    {
        $tag = Tag::create($request->validated());

        return response()->json([
            "data" => new TagResource($tag),
            "message" => "Tag Created Successfully",
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag): JsonResponse
    {
        return response()->json([
            "data" => new TagResource($tag),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        $tag->update($request->validated());

        return response()->json([
            "data" => new TagResource($tag),
            "message" => "Tag Updated Successfully",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        return response()->json([
            "message" => "Tag Deleted Successfully",
        ], 200);
    }
}
