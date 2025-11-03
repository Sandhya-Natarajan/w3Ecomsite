<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Http\Resources\OrderResource;
use App\Jobs\SendOrderEmailJob;
use Illuminate\Http\JsonResponse;
use App\Services\OrderService;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderEmail;
use Illuminate\support\facades\Log;




class OrderController extends Controller
{

    public function __construct(protected OrderService $orderService)
    {
        //
    }

    public function index(Request $request): JsonResponse
    {

        $orders = $this->orderService->listOrders(
            $request->get("search"),
            $request->get("per_page"),
        );

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
        $order = $this->orderService->createOrder($request->validated());

        try {
            SendOrderEmailJob::dispatch($order)->delay(now()->addseconds(10));

            event(new OrderPlaced($order));
        } catch (\Throwable $e) {
            Log::channel('orderlog')->error('Mail sending failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json(['error' => 'Something went wrong!'], 500);
        }


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
        return response()->json([
            'data' => new OrderResource($order)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Order $order): JsonResponse
    {
        $order = $this->orderService->updateOrder($order, $request->validated());

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
        $this->orderService->deleteOrder($order);

        return response()->json([
            'message' => 'Order deleted successfully.'
        ], 200);
    }
}
