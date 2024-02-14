<?php

namespace Baham\Order\Repository;

use Baham\Order\Contract\OrderRepositoryInterface;
use Baham\Order\Models\Order;
use Baham\Transaction\Models\Transaction;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function create($count){
        return Order::create([
            'user_id' => auth()->id(),
            'count' => $count,
            'number_remaining' => $count,
            'status' => Order::STATUS_ACTIVE,
        ]);
    }
    public function getOrdersForUserToFollow(int $userId)
    {
        return $this->order->notFollowedByUser($userId)
            ->activeOrder()
//            ->notUserOrder($userId)
            ->paginate(10);
    }

    public function decrementRemainingOrders(int $orderId)
    {
        $order = $this->order->findOrFail($orderId);
        $order->number_remaining -= 1;
        $order->save();
    }

    public function updateOrderStatusToClosed(int $orderId)
    {
        $order = $this->order->findOrFail($orderId);
        $order->status = 0;
        $order->save();
    }

    public function hideOrderForUser(int $orderId, int $userId)
    {
        $order = $this->order->findOrFail($orderId);
        $order->hidden_from_users()->attach($userId);
    }

    public function createTransaction(int $userId, int $orderId, int $type , int $count)
    {
        Transaction::create([
            'user_id' => $userId,
            'order_id' => $orderId,
            'type' => $type,
            'count' => $count
        ]);
    }
}
