<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderEmail;

class OrderService
{

    public function __construct(protected OrderRepository $orderRepository)
    {
        //
    }


    public function listOrders(?string $search, int $perPage)
    {

        return $this->orderRepository->getAllOrders($search, $perPage);
    }

    public function createOrder(array $validated): ?Order
    {

        $todayOrders = Order::whereDate('created_at', now()->toDateString())->count();

        if ($todayOrders >= 3) {
            abort(403, 'Maximum 3 orders per day allowed');
        }

        $total = collect($validated['products'])->sum(fn($p) => $p['quantity'] * $p['price']);

        $order = $this->orderRepository->create([
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'status'       => $validated['status'] ?? 'active',
        ]);

        $pivotData = collect($validated['products'])->mapWithKeys(fn($p) => [
            $p['product_id'] => [
                'quantity' => $p['quantity'],
                'price' => $p['price'],
            ],
        ]);

        $order->products()->attach($pivotData);

        Mail::to($order->user->email)->send(new OrderEmail($order));

        return $order->load('user', 'products');
    }


    public function updateOrder(Order $order, array $validated): ?Order
    {
        // Recalculate total amount if products are provided
        if (isset($validated['products'])) {
            $total = collect($validated['products'])->sum(fn($p) => $p['quantity'] * $p['price']);

            $order->total_amount = $total;

            // Sync updated products to pivot table
            $pivotData = collect($validated['products'])->mapWithKeys(fn($p) => [
                $p['product_id'] => [
                    'quantity' => $p['quantity'],
                    'price' => $p['price'],
                ]
            ])->toArray();

            $order->products()->sync($pivotData);
        }

        // Update status if provided
        if (isset($validated['status'])) {
            $order->status = $validated['status'];
        }

        // Update other validated fields (if any)
        $this->orderRepository->update($order, $validated);

        // Load relations
        return $order->load('user', 'products.tags');
    }

    public function deleteOrder(Order $order)
    {
        $this->orderRepository->delete($order);
    }
}
