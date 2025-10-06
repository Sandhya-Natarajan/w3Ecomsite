<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Tag;
use App\Http\Resources\TagResource;
use App\Http\Resources\TagCollection;


class TagController extends Controller
{


    //Display the specified resource.
    public function tag($id)
    {
        $tagId = $id;
        $tag = Tag::find($tagId);
        if(!$tag) {
            return response()->json(['message'=>'Tag not found'],404);
        }
        return new TagResource($tag);
    }



    //Display a listing of the resource.
    public function tags()
    {
        $tags = Tag::all();
        if($tags->isEmpty()) {
            return response()->json(['message'=>'Tag not found'],404);
        }
        return new TagCollection($tags);
    }



    //Store a newly created resource in storage.
    public function AddTag(TagRequest $request)
    {
        //create Tag
         $tag = Tag::create([
            'name'=> $request['name']
        ]);

         return response()->json(['message'=>'Tag Created Successfully...']);

    }



    //Update the specified resource in storage.
    public function UpdateTag(TagRequest $request, $id)
    {
        $tag = Tag::find($id);

        $tag->update([
            'name'=> $request['name']
        ]);

        return response()->json(['message'=>'Tag Updated Successfully...']);


    }





    //Remove the specified resource from storage.
    public function DeleteTag(string $id)
    {
        $tag = Tag::find($id);
        $tag->delete();

        return response()->json(['message'=>'Tag Deleted Successfully...']);


    }

}
