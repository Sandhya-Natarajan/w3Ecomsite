<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\Product;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;



class OrderController extends Controller
{

    public function index(Request $request): JsonResponse
    {

        $orders = Order::with('products')
            ->search($request->get('search'))
            ->latest()
            ->paginate($request->get('per_page', 10));

        return response()->json([
            'data' => OrderResource::collection($orders),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (Order::today()->count() >= 5) {
            return response()->json(['message' => 'Maximum 2 orders per day allowed'], 403);
        }

        $total = collect($validated['products'])->sum(fn($p) => $p['quantity'] * $p['price']);

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'status'       => $request->status,
        ]);

        $pivotData = collect($validated['products'])->mapWithKeys(fn($p) => [
            $p['product_id'] => [
                'quantity' => $p['quantity'],
                'price' => $p['price'],
            ],
        ]);

        $order->products()->attach($pivotData);

        //Load user and products (with pivot data)
        $order->load('user', 'products');

        return response()->json([
            'message' => 'Order created successfully',
            'data' => new OrderResource($order),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): JsonResponse
    {

        $order->load('user', 'products');

        return response()->json([
            'data' => new OrderResource($order)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Order $order): JsonResponse
    {
        // Recalculate total amount
        $total = collect($request->products)->sum(fn($p) => $p['quantity'] * $p['price']);

        // Update order details
        $order->update(array_merge($request->validated(), ['total_amount' => $total]));

        // Sync updated products to pivot table
        if ($request->filled('products')) {
            $order->products()->sync(
                collect($request->products)->mapWithKeys(fn($p) => [
                    $p['product_id'] => [
                        'quantity' => $p['quantity'],
                        'price' => $p['price'],
                    ]
                ])->toArray()
            );
        }

        $order->load('user', 'products.tags');

        return response()->json([
            'data' => new OrderResource($order),
            'message' => 'Order updated successfully.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully.'
        ], 200);
    }
}
