<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;



class OrderController extends Controller
{


    //Display the specified resource.
    public function order($id)
    {
        $orderId = $id;
        $order = Order::with('orderDetails.product')->find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return new OrderResource($order);
    }




    //Display a listing of the resource.
    public function orders()
    {
        $orders = Order::with('orderDetails.product')->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No orders found'], 404);
        }

        return new OrderCollection($orders);
    }



    //Store a newly created resource in storage.
    public function AddOrder(OrderRequest $request)
    {

        $userId = Auth::id();
        $products = $request->input('products',[]);
        $status = $request->input('status');

        // Calculate total order amount
        $totalAmount = 0;
        foreach ($products as $item) {
            $totalAmount += $item['price'] * $item['qty'];
        }

        // Create order
        $order = Order::create([
            'user_id' => $userId,
            'status' => $status,
            'total_amount' => $totalAmount,
        ]);

        // Insert order details
        foreach ($products as $item) {
            $order->orderDetails()->create([
                'product_id' => $item['product_id'],
                'quantity'   => $item['qty'],
                'price'      => $item['price'],
            ]);
        }

        // Load order details with product info
        $order->load('orderDetails.product');

        // Build response
        return response()->json([
            'message' => 'Order placed successfully',
            'order' => [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'status' => $order->status,
                'total_amount' => $order->total_amount,
                'products' => $order->orderDetails->map(function ($detail) {
                    return [
                        'product_id' => $detail->product_id,
                        'product_name' => $detail->product->name ?? null,
                        'price' => $detail->price,
                        'quantity' => $detail->quantity,
                        'total' => $detail->price * $detail->quantity,
                    ];
                }),
            ]
        ], 201);
    }




    //Update the specified resource in storage.
    public function updateOrder(OrderRequest $request, $id)
    {
        // Find the order
        $order = Order::with('orderDetails')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update status if provided
        if ($request->has('status')) {
            $order->status = $request->input('status');
        }

        // Update products if provided
        if ($request->has('products')) {
            $products = $request->input('products',[]);

            // Delete existing order details
            $order->orderDetails()->delete();

            $totalAmount = 0;

            // Insert new order details
            foreach ($products as $item) {
                $order->orderDetails()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);

                $totalAmount += $item['price'] * $item['qty'];
            }

            // Update total amount
            $order->total_amount = $totalAmount;
        }

        $order->save();

        // Load order details with product info
        $order->load('orderDetails.product');

        return response()->json([
            'message' => 'Order updated successfully',
            'order' => new OrderResource($order)
        ], 200);
    }



    //Remove the specified resource from storage.
    public function DeleteOrder($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        // Delete related order details first
        $order->orderDetails()->delete();

        // Delete the order
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully'
        ], 200);
    }

}
