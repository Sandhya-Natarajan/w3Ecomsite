<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{

    public function getAllOrders(?string $search, int $perPage = 10)
    {

        $query = Order::query();

        if (!empty($search)) {
            $query->where("status", "like", "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data): Order
    {

        return Order::create($data);
    }

    public function find(int $id): ?Order
    {
        return Order::with('user', 'products')->find($id);
    }

    public function update(Order $order, array $data): Order
    {

        $order->update($data);
        return $order;
    }


    public function delete(Order $order): bool
    {
        return $order->delete();
    }
}
