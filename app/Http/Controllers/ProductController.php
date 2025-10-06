<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ProductTag;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;


class ProductController extends Controller
{

    //Display the specified resource.
    public function product($id)
    {
        $productId = $id;
        $product = Product::with('productTag')->find($productId);
        if(!$product) {
            return response()->json(['message'=>'Product not found'],404);
        }
        return new ProductResource($product);
    }



    //Display a listing of the resource.
    public function products()
    {
        $products = Product::with('productTag')->get();
        if($products->isEmpty()) {
            return response()->json(['message'=>'Product not found'],404);
        }
        return new ProductCollection($products);
    }



    //Store a newly created resource in storage.
    public function AddProduct(ProductRequest $request)
    {
        //Create Product
        $product = Product::create([
            'name'=> $request['name'],
            'cat_id'=> $request['cat_id'],
            'desc'=> $request['desc'],
            'price'=> $request['price'],
            'stock'=> $request['stock']
        ]);

        //Create Product Tags
         if ($request->has('tag_id')) {
            foreach ($request->tag_id as $tagId) {
                ProductTag::create([
                    'product_id' => $product->id,
                    'tag_id' => $tagId,
                ]);
            }
        }

        return response()->json(['message'=>'Product Created Successfully...']);

    }



    //Update the specified resource in storage.
    public function UpdateProduct(ProductRequest $request){

         // Find product
        $product = Product::find($request->id);
        if(!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Update product info
        $product->update([
            'name' => $request->name,
            'cat_id'=> $request->cat_id,
            'desc'=> $request->desc,
            'price' => $request->price,
            'stock'=> $request->stock
        ]);

        // Delete old tags
        $product->productTag()->delete();

        // Add new tags
        if ($request->has('tag_id')) {
            foreach ($request->tag_id as $tagId) {
                ProductTag::create([
                    'product_id' => $product->id,
                    'tag_id' => $tagId,
                ]);
            }
        }


        return response()->json(['message' => 'Product Updated Successfully']);


    }



     //Remove the specified resource from storage.
    public function DeleteProduct($id)
    {
        $productId = $id;
        $product = Product::find($productId);
        $product->delete();

        return response()->json(['message'=>'Product Deleted Successfully...']);

    }

}
