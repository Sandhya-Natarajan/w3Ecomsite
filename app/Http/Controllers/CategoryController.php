<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;


class CategoryController extends Controller
{


    //Display the specified resource.
    public function category($id)
    {
        $categoryId = $id;
        $category = Category::find($categoryId);
        if(!$category) {
            return response()->json(['message'=>'Category not found'],404);
        }
        return new CategoryResource($category);
    }



    //Display a listing of the resource.
    public function categories()
    {
        $categories = Category::all();
        if($categories->isEmpty()) {
            return response()->json(['message'=>'Category not found'],404);
        }
        return new CategoryCollection($categories);
    }



    //Store a newly created resource in storage.
    public function AddCategory(CategoryRequest $request)
    {
         $category = Category::create([
            'name'=> $request['name'],
            'desc'=> $request['desc']
        ]);

         return response()->json(['message'=>'Category Created Successfully...']);

    }




    //Update the specified resource in storage.
    public function UpdateCategory(CategoryRequest $request, $id)
    {
        $category = Category::find($id);

        $category->update([
            'name'=> $request['name'],
            'desc'=> $request['desc']
        ]);

        return response()->json(['message'=>'Category Updated Successfully...']);


    }



    //Remove the specified resource from storage.
    public function DeleteCategory($id)
    {
        $category = Category::find($id);
        $category->delete();

        return response()->json(['message'=>'Category Deleted Successfully...']);


    }

}
